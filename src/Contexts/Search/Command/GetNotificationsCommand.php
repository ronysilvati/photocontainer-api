<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Command;

class GetNotificationsCommand
{
    /**
     * @var int
     */
    private $userId;

    /**
     * GetNotificationsCommand constructor.
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}