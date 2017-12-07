<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;


use PhotoContainer\PhotoContainer\Contexts\User\Command\CreateUserCommand;
use PhotoContainer\PhotoContainer\Contexts\User\Command\FindFreeSlotForUserCommand;
use PhotoContainer\PhotoContainer\Contexts\User\Command\FindUserCommand;
use PhotoContainer\PhotoContainer\Contexts\User\Command\RequestPwdChangeCommand;
use PhotoContainer\PhotoContainer\Contexts\User\Command\UpdatePasswordCommand;
use PhotoContainer\PhotoContainer\Contexts\User\Command\UpdateUserCommand;
use PhotoContainer\PhotoContainer\Contexts\User\Command\UploadProfileImageCommand;

use PhotoContainer\PhotoContainer\Infrastructure\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends Controller
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function findUser(ServerRequestInterface $request, ResponseInterface $response)
    {
        $queryParams = $request->getQueryParams();

        $domainResponse = $this->commandBus()->handle(
            new FindUserCommand($queryParams['id'] ?? null, $queryParams['email'] ?? null)
        );
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function findFreeSlotForUser(ResponseInterface $response)
    {
        $domainResponse = $this->commandBus()->handle(new FindFreeSlotForUserCommand());
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createUser(ServerRequestInterface $request, ResponseInterface $response)
    {
        $domainResponse = $this->commandBus()->handle(new CreateUserCommand($request->getParsedBody()));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function updateUser(ServerRequestInterface $request, ResponseInterface $response, int $id)
    {
        $domainResponse = $this->commandBus()->handle(new UpdateUserCommand($id, $request->getParsedBody()));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createProfileImage(ServerRequestInterface $request, ResponseInterface $response, int $id)
    {
        $files = $request->getUploadedFiles();
        if (empty($files)) {
            return $response->withJson(['message' => 'Imagem não enviada ou erro durante o envio.'], 500);
        }

        $domainResponse = $this->commandBus()->handle(new UploadProfileImageCommand($id, $files['profile_image']));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function requestPwdChange(ServerRequestInterface $request, ResponseInterface $response)
    {
        $body = $request->getParsedBody();

        $domainResponse = $this->commandBus()->handle(new RequestPwdChangeCommand($body['email']));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function updatePassword(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();

        $domainResponse = $this->commandBus()->handle(new UpdatePasswordCommand($data['token'], $data['password']));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}