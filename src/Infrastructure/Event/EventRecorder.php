<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Event;

class EventRecorder
{
    /**
     * @var array
     */
    private $events;

    public static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * @param $event
     */
    public function record(Event $event): void
    {
        if ($this->events === null) {
            $this->events = [];
        }

        $this->events[] = $event;
    }

    /**
     * @return array
     */
    public function pullEvents(): ?array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
