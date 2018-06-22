<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

class CategoryCollectionResponse implements \JsonSerializable
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function jsonSerialize()
    {
        $out = [];
        foreach ($this->collection as $item) {
            $out[] = [
                'id' => $item->getId(),
                'description' => $item->getDescription(),
            ];
        }

        return $out;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
