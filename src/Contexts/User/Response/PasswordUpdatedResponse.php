<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Response;

class PasswordUpdatedResponse implements \JsonSerializable
{
    public function getHttpStatus(): int
    {
        return 204;
    }

    public function jsonSerialize()
    {
        return [];
    }
}