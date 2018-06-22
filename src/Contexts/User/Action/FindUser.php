<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\ProfileImageHelper;

use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

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
     * @param int|null $id
     * @param string|null $email
     * @return UserResponse|DomainExceptionResponse
     */
    public function handle(int $id = null, string $email = null)
    {
        $user = $this->userRepository->findUser($id, $email);

        $profileImageUri = $this->profileImageHelper->resolveUri($user->getId());

        return new UserResponse($user, $profileImageUri);
    }
}
