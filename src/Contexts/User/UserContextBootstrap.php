<?php

namespace PhotoContainer\PhotoContainer\Contexts\User;

use PhotoContainer\PhotoContainer\Contexts\User\Action\CreateUser;
use PhotoContainer\PhotoContainer\Contexts\User\Action\FindFreeSlotForUser;
use PhotoContainer\PhotoContainer\Contexts\User\Action\FindUser;
use PhotoContainer\PhotoContainer\Contexts\User\Action\RequestPwdChange;
use PhotoContainer\PhotoContainer\Contexts\User\Action\UpdatePassword;
use PhotoContainer\PhotoContainer\Contexts\User\Action\UpdateUser;
use PhotoContainer\PhotoContainer\Contexts\User\Action\UploadProfileImage;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Persistence\EloquentUserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\ImageHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\TokenGeneratorHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->get('/users', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $args = $request->getQueryParams();

            $id = isset($args['id']) ? $args['id'] : null;
            $email = isset($args['email']) ? $args['email'] : null;

            $action = new FindUser(
                new EloquentUserRepository($container['DatabaseProvider']),
                $container['ProfileImageHelper']
            );

            $actionResponse = $action->handle($id, $email);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/users/satisfyPreConditions', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $action = new FindFreeSlotForUser(new EloquentUserRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle();

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->post('/users', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $data = $request->getParsedBody();

            $details = new Details(
                null,
                isset($data['details']['blog']) ? $data['details']['blog'] : '',
                isset($data['details']['instagram']) ? $data['details']['instagram'] : '',
                isset($data['details']['facebook']) ? $data['details']['facebook'] : '',
                isset($data['details']['pinterest']) ? $data['details']['pinterest'] : '',
                isset($data['details']['site']) ? $data['details']['site'] : '',
                isset($data['details']['phone']) ? $data['details']['phone'] : '',
                isset($data['details']['birth']) ? $data['details']['birth'] : ''
            );

            $profile = new Profile(null, null, (int) $data['profile'], null);
            $user = new User(null, $data['name'], $data['email'], $data['password'], $details, $profile);

            $action = new CreateUser(
                new EloquentUserRepository($container['DatabaseProvider']),
                $container['CryptoMethod'],
                $container['AtomicWorker']
            );

            $actionResponse = $action->handle($user);

            $container['EventEmitter']->addContextEvents($action->getEvents());

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->patch('/users/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $data = $request->getParsedBody();

            $action = new UpdateUser(
                new EloquentUserRepository($container['DatabaseProvider']),
                $container['CryptoMethod']
            );
            $actionResponse = $action->handle($args['id'], $data);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->post('/users/{id}/profileImage', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            if (!isset($_FILES['profile_image']['error']) || is_array($_FILES['profile_image']['error'])) {
                return $response->withJson(['message' => 'Erro no recebimento da imagem.'], 500);
            }

            $action = new UploadProfileImage(
                new EloquentUserRepository($container['DatabaseProvider']),
                new ImageHelper(getenv('SHARED_PATH').'/profile_images/'),
                $container['ProfileImageHelper']
            );
            $actionResponse = $action->handle((int) $args['id'], $_FILES['profile_image']);

            return  $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->post('/users/requestPasswordChange', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $data = $request->getParsedBody();

            if (!isset($data['email'])) {
                throw new DomainViolationException('O email deve ser enviado.');
            }

            $action = new RequestPwdChange(
                new EloquentUserRepository($container['DatabaseProvider']),
                new TokenGeneratorHelper(),
                $container['EmailHelper'],
                $container['AtomicWorker']
            );
            $actionResponse = $action->handle($data['email']);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->post('/users/updatePassword', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $data = $request->getParsedBody();

            $action = new UpdatePassword(
                new EloquentUserRepository($container['DatabaseProvider']),
                $container['CryptoMethod'],
                $container['AtomicWorker']
            );
            $actionResponse = $action->handle($data['token'], $data['password']);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        return $slimApp;
    }
}
