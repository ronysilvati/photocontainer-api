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

        $slimApp->app->get('/users', function (ServerRequestInterface $request, ResponseInterface $response) {
            $args = $request->getQueryParams();

            $id = isset($args['id']) ? $args['id'] : null;
            $email = isset($args['email']) ? $args['email'] : null;

            $action = new FindUser(new EloquentUserRepository());
            $actionResponse = $action->handle($id, $email);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->post('/users', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $data = $request->getParsedBody();

                $details = new Details(
                    null,
                    isset($data['details']['blog']) ? $data['details']['blog'] : '',
                    isset($data['details']['instagram']) ? $data['details']['instagram'] : '',
                    isset($data['details']['facebook']) ? $data['details']['facebook'] : '',
                    isset($data['details']['linkedin']) ? $data['details']['linkedin'] : '',
                    isset($data['details']['site']) ? $data['details']['site'] : '',
                    isset($data['details']['gender']) ? $data['details']['gender'] : '',
                    isset($data['details']['phone']) ? $data['details']['phone'] : '',
                    isset($data['details']['birth']) ? $data['details']['birth'] : ''
                );

                $profile = new Profile(null, null, (int) $data['profile'], null);
                $user = new User(null, $data['name'], $data['email'], $data['password'], $details, $profile);

                $crypto = $container['CryptoMethod']->hash($data['password']);

                $action = new CreateUser(new EloquentUserRepository());
                $actionResponse = $action->handle($user, $crypto);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->patch('/users/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $data = $request->getParsedBody();

                $crypto = null;
                if (isset($data['password'])) {
                    $crypto = $container['CryptoMethod']->hash($data['password']);
                }

                $action = new UpdateUser(new EloquentUserRepository());
                $actionResponse = $action->handle($args['id'], $data, $crypto);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        return $slimApp;
    }
}