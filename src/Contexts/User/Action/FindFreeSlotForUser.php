<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Action;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\HasSlotsResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\NoUserSlotsResponse;


class FindFreeSlotForUser
{
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
