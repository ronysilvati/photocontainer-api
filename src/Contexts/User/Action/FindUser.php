<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Command\FindUserCommand;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\ProfileImageHelper;

class FindUser
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ProfileImageHelper
     */
    private $profileImageHelper;

    /**
     * FindUser constructor.
     * @param UserRepository $userRepository
     * @param ProfileImageHelper $profileImageHelper
     */
    public function __construct(UserRepository $userRepository, ProfileImageHelper $profileImageHelper)
    {
        $this->userRepository = $userRepository;
        $this->profileImageHelper = $profileImageHelper;
    }

    /**
     * @param FindUserCommand $command
     * @return UserResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     */
    public function handle(FindUserCommand $command): \PhotoContainer\PhotoContainer\Contexts\User\Response\UserResponse
    {
        $user = $this->userRepository->findUser($command->getId(), $command->getEmail());

        if (!$user) {
            throw new DomainViolationException('Usuário não encontrado.');
        }

        $profileImageUri = $this->profileImageHelper->resolveUri($user->getId());

        return new UserResponse($user, $profileImageUri);
    }
}
