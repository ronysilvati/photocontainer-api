<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Command;

class FindEventPhotosPublisherCommand
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * @var int
     */
    private $userId;

    /**
     * FindEventPhotosPublisherCommand constructor.
     * @param int $eventId
     * @param int $userId
     */
    public function __construct(int $eventId, int $userId)
    {
        $this->eventId = $eventId;
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}