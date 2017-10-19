<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class EventTag
{
    private $event_id;
    private $tag_id;

    /**
     * EventTag constructor.
     * @param $event_id
     * @param $tag_id
     */
    public function __construct($event_id, $tag_id)
    {
        $this->event_id = $event_id;
        $this->tag_id = $tag_id;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @param mixed $event_id
     */
    public function changeEventId($event_id): void
    {
        $this->event_id = $event_id;
    }

    /**
     * @return mixed
     */
    public function getTagId()
    {
        return $this->tag_id;
    }

    /**
     * @param mixed $tag_id
     */
    public function changeTagId($tag_id): void
    {
        $this->tag_id = $tag_id;
    }
}
