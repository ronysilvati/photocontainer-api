<?php

namespace PhotoContainer\PhotoContainer\Infrastructure;

class NoContentResponse implements \JsonSerializable
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