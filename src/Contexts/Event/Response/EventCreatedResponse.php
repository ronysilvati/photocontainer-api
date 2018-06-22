<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;

class EventCreatedResponse implements \JsonSerializable
{
    /**
     * @var int
     */
    private $httpStatus = 201;

    /**
     * @var Event
     */
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->event->getId(),
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
