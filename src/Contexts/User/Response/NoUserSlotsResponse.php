<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Response;

class NoUserSlotsResponse implements \JsonSerializable
{
    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 300;
    }

    public function jsonSerialize()
    {
        return ['message' => 'Todos os slots de cadastro foram utilizados.'];
    }
}
