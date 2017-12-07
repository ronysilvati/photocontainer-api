<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Command;

class UpdateEventCommand
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * @var array
     */
    private $data;

    /**
     * UpdateEventCommand constructor.
     * @param int $eventId
     * @param array $data
     */
    public function __construct(int $eventId, array $data)
    {
        $this->eventId = $eventId;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}