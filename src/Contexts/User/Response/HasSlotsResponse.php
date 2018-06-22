<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Response;

class HasSlotsResponse
{
    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 204;
    }

    public function jsonSerialize()
    {
        return [];
    }
}