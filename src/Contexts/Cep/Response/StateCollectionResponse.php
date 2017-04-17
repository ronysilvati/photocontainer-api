<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Response;

class StateCollectionResponse implements \JsonSerializable
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function jsonSerialize()
    {
        return $this->collection;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
