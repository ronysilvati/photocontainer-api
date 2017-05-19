<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Event;

trait EventGeneratorTrait
{
    /**
     * @var array
     */
    private $events;

    /**
     * @param string $eventName
     * @param $data
     */
    public function addEvent(string $eventName, $data): void
    {
        if ($this->events === null) {
            $this->events = [];
        }

        $this->events[$eventName][] = $data;
    }

    /**
     * @return array
     */
    public function getEvents(): ?array
    {
        return $this->events;
    }
}