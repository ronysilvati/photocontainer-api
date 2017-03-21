<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;

class EventCreatedResponse implements \JsonSerializable
{
    private $httpStatus = 201;
    private $message;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->selfReference = "events/{$this->event->getId()}";
    }

    function jsonSerialize()
    {
        return [
            "id" => $this->event->getId(),
            "_links" => [
                "_self" => ['href' => $this->selfReference],
            ],
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}