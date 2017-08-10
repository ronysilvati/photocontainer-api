<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Auth\Action\AuthenticateUser;
use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\Auth;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\JwtGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param AuthenticateUser $action
     * @return mixed
     */
    public function login(ServerRequestInterface $request, ResponseInterface $response, AuthenticateUser $action)
    {
        $data = $request->getParsedBody();

        $domainResponse = $action->handle(new Auth($data['user'], $data['password']), new JwtGenerator('secret'));

        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}