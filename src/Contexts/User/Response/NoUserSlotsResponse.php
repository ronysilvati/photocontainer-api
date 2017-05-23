<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Response;

class NoUserSlotsResponse implements \JsonSerializable
{
    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 500;
    }

    public function jsonSerialize(): array
    {
        return ['message' => 'Todos os slots de cadastro foram utilizados.'];
    }
}
