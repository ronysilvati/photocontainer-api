<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

class EventRemovedResponse implements \JsonSerializable
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "message" => "Evento removido.",
            "_links" => [
                "_self" => ['href' => "/events/".$this->id],
            ],
        ];    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 201;
    }
}