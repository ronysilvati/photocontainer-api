<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Response;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\State;

class StateCollectionResponse implements \JsonSerializable
{
    protected $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function jsonSerialize()
    {
        return array_map(function (State $value) {
            return ['id' => $value->getId(), 'name' => utf8_encode($value->getName())];
        }, $this->collection);
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
