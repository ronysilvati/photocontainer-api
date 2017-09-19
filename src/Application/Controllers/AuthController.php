<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Auth\Action\AuthenticateUser;
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
//        $data = $request->getParsedBody();

//        $domainResponse = $action->handle(new Auth($data['user'], $data['password']), new JwtGenerator('secret'));
        $domainResponse = $action->handle($request);

        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}