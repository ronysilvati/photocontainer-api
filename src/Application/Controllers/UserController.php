<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

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
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindUser $action
     * @return mixed
     */
    public function findUser(ServerRequestInterface $request, ResponseInterface $response, FindUser $action)
    {
        $args = $request->getQueryParams();

        $id = $args['id'] ?? null;
        $email = $args['email'] ?? null;

        $actionResponse = $action->handle($id, $email);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindFreeSlotForUser $action
     * @return mixed
     */
    public function findFreeSlotForUser(ServerRequestInterface $request, ResponseInterface $response, FindFreeSlotForUser $action)
    {
        $actionResponse = $action->handle();
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param CreateUser $action
     * @return mixed
     * @throws DomainViolationException
     */
    public function createUser(ServerRequestInterface $request, ResponseInterface $response, CreateUser $action)
    {
        $data = $request->getParsedBody();

        $details = new Details(
            null,
            $data['details']['blog'] ?? '',
            $data['details']['instagram'] ?? '',
            $data['details']['facebook'] ?? '',
            $data['details']['pinterest'] ?? '',
            $data['details']['site'] ?? '',
            $data['details']['phone'] ?? '',
            $data['details']['birth'] ?? ''
        );

        $profile = new Profile(null, null, (int) $data['profile'], null);
        $user = new User(null, $data['name'], $data['email'], $data['password'], $details, $profile);

        $actionResponse = $action->handle($user);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param UpdateUser $action
     * @param int $id
     * @return mixed
     */
    public function updateUser(ServerRequestInterface $request, ResponseInterface $response, UpdateUser $action, int $id)
    {
        $data = $request->getParsedBody();

        $actionResponse = $action->handle($id, $data);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());

    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param UploadProfileImage $action
     * @param string $id
     * @return mixed
     * @throws DomainViolationException
     * @throws \Exception
     */
    public function createProfileImage(
        ServerRequestInterface $request,
        ResponseInterface $response,
        UploadProfileImage $action,
        string $id
    ) {
        if (!isset($_FILES['profile_image']['error']) || is_array($_FILES['profile_image']['error'])) {
            return $response->withJson(['message' => 'Erro no recebimento da imagem.'], 500);
        }

        $actionResponse = $action->handle((int) $id, $_FILES['profile_image']);

        return  $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param RequestPwdChange $action
     * @return mixed
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     */
    public function requestPwdChange(
        ServerRequestInterface $request,
        ResponseInterface $response,
        RequestPwdChange $action
    ) {
        $data = $request->getParsedBody();

        if (!isset($data['email'])) {
            throw new DomainViolationException('O email deve ser enviado.');
        }

        $actionResponse = $action->handle($data['email']);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param UpdatePassword $action
     * @return mixed
     * @throws DomainViolationException
     */
    public function updatePassword(
        ServerRequestInterface $request,
        ResponseInterface $response,
        UpdatePassword $action
    ) {
        $data = $request->getParsedBody();

        $actionResponse = $action->handle($data['token'], $data['password']);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }
}