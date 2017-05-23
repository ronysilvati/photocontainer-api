<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Email\NewUserEmail;
use PhotoContainer\PhotoContainer\Contexts\User\Response\HasSlotsResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\NoUserSlotsResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\UserCreatedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventGeneratorTrait;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\AtomicWorker;


class FindFreeSlotForUser
{
    use EventGeneratorTrait;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * FindFreeSlotForUser constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return HasSlotsResponse|NoUserSlotsResponse
     */
    public function handle()
    {
        if (getenv('MAX_USER_SLOTS') === false) {
            return new HasSlotsResponse();
        }

        if($this->userRepository->isUserSlotsAvailable(getenv('MAX_USER_SLOTS'))) {
            return new HasSlotsResponse();
        }

        return new NoUserSlotsResponse();
    }
}
