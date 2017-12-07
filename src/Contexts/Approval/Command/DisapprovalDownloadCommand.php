<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Command;

class DisapprovalDownloadCommand
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * @var int
     */
    private $publisherId;

    /**
     * RequestDownloadCommand constructor.
     * @param int $eventId
     * @param int $publisherId
     */
    public function __construct(int $eventId, int $publisherId)
    {
        $this->eventId = $eventId;
        $this->publisherId = $publisherId;
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
    public function getPublisherId(): int
    {
        return $this->publisherId;
    }
}