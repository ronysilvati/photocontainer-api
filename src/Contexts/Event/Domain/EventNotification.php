<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class EventNotification
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * Notification constructor.
     * @param Event $event
     * @param Publisher $publisher
     */
    public function __construct(Event $event, Publisher $publisher)
    {
        $this->event = $event;
        $this->publisher = $publisher;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return Publisher
     */
    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }
}
