<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

class BroadcastResponse implements \JsonSerializable
{
    /**
     * @return void
     */
    public function jsonSerialize(): void
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
