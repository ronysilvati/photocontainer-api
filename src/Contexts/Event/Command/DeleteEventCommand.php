<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Command;

class DeleteEventCommand
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * DeleteEventCommand constructor.
     * @param int $eventId
     */
    public function __construct(int $eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }
}