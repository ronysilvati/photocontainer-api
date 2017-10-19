<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Event;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Infrastructure\Event\Event;

class PhotographerCreated implements Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserCreated constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function getName(): string
    {
        return 'photographer_registered';
    }
}