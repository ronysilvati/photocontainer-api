<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

class EventRemovedResponse implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * EventRemovedResponse constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'message' => 'Evento removido.',
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 201;
    }
}
