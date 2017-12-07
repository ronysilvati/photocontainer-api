<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Command;

class FindEventCommand
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * FindEventCommand constructor.
     * @param $eventId
     */
    public function __construct(?int $eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * @return int
     */
    public function getEventId(): ?int
    {
        return $this->eventId;
    }
}