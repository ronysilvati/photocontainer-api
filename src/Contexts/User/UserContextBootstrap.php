<?php

namespace PhotoContainer\PhotoContainer\Contexts\User;

use PhotoContainer\PhotoContainer\Contexts\User\Action\CreateUser;
use PhotoContainer\PhotoContainer\Contexts\User\Action\FindUser;
use PhotoContainer\PhotoContainer\Contexts\User\Action\UpdateUser;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Persistence\EloquentUserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
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

            $action = new FindUser(new EloquentUserRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($id, $email);

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
                $container['CryptoMethod']
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

        return $slimApp;
    }
}
