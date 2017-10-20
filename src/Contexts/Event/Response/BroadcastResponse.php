<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

class BroadcastResponse implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return null;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 204;
    }
}
