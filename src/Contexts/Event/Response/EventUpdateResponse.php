<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;

class EventUpdateResponse implements \JsonSerializable
{
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function jsonSerialize()
    {
        return [
            "message" => "Update realizado.",
            "_links" => [
                "_self" => ['href' => "/events/".$this->event->getId()],
            ],
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 201;
    }
}
