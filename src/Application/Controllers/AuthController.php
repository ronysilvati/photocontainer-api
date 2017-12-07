<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Auth\Command\AuthenticateUserCommand;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Controller;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    public function authenticate(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $request->getParsedBody();

        $domainResponse = $this->commandBus()->handle(
            new AuthenticateUserCommand($body['user'], $body['password'])
        );

        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}