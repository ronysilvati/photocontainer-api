<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;

class EventUpdateResponse implements \JsonSerializable
{
    /**
     * @var Event
     */
    private $event;

    /**
     * EventUpdateResponse constructor.
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'message' => 'Update realizado.',
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
