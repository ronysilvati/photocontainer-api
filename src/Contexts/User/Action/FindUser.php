<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class FindUser
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * FindUser constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int|null $id
     * @param string|null $email
     * @return UserResponse|DomainExceptionResponse
     */
    public function handle(int $id = null, string $email = null)
    {
        try {
            $user = $this->userRepository->findUser($id, $email);
            return new UserResponse($user);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
