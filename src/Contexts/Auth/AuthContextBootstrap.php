<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth;

use PhotoContainer\PhotoContainer\Contexts\Auth\Action\AuthenticateUser;
use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\Auth;
use PhotoContainer\PhotoContainer\Contexts\Auth\Persistence\EloquentAuthRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\JwtGenerator;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->post('/login', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $data = $request->getParsedBody();

            $action = new AuthenticateUser(new EloquentAuthRepository(), $container['CryptoMethod']);
            $domainResponse =  $action->handle(new Auth($data['user'], $data['password']), new JwtGenerator('secret'));

            return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
        });

        return $slimApp;
    }
}
