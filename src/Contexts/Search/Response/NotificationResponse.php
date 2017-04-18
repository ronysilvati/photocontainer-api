<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Notification;

class NotificationResponse implements \JsonSerializable
{
    private $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function jsonSerialize()
    {
        return [
            'all' => $this->notification->getTotal(),
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
