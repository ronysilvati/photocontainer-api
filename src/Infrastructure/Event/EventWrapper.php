<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Event;

use League\Event\AbstractEvent;

class EventWrapper extends AbstractEvent
{
    /**
     * @var Event
     */
    private $event;

    /**
     * EventWrapper constructor.
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function getData(): Event
    {
        return $this->event;
    }

    public function getName()
    {
        return $this->event->getName();
    }
}