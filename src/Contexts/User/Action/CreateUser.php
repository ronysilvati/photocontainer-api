<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\NoUserSlotsResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserCreatedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\AtomicWorker;


class CreateUser
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var CryptoMethod
     */
    protected $cryptoMethod;

    /**
     * @var AtomicWorker
     */
    private $worker;

    /**
     * CreateUser constructor.
     * @param UserRepository $userRepository
     * @param CryptoMethod $cryptoMethod
     * @param AtomicWorker $worker
     */
    public function __construct(UserRepository $userRepository, CryptoMethod $cryptoMethod, AtomicWorker $worker)
    {
        $this->userRepository = $userRepository;
        $this->cryptoMethod = $cryptoMethod;
        $this->worker = $worker;
    }

    /**
     * @param User $user
     * @return NoUserSlotsResponse|UserCreatedResponse
     * @throws DomainViolationException
     */
    public function handle(User $user)
    {
        if (getenv('MAX_USER_SLOTS') !== false && !$this->userRepository->isUserSlotsAvailable(getenv('MAX_USER_SLOTS'))) {
            return new NoUserSlotsResponse();
        }

        if(!$this->userRepository->isUserUnique($user->getEmail())) {
            throw new DomainViolationException('Este email já está sendo utilizado por outro usuário.');
        }

        $user = $this->worker->execute(function () use ($user){
            $encrypted = null === $user->getPwd() ? '' : $this->cryptoMethod->hash($user->getPwd());
            return $this->userRepository->createUser($user, $encrypted);
        });

        return new UserCreatedResponse($user);
    }
}
