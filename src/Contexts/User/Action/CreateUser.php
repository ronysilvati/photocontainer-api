<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserCreatedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Entity;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class CreateUser
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(Entity $user, string $encryptedPwd)
    {
        try {
            $user = $this->userRepository->createUser($user, $encryptedPwd);
            return new UserCreatedResponse($user);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}