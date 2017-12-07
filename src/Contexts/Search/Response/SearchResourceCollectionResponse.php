<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

class SearchResourceCollectionResponse implements \JsonSerializable
{
    /**
     * @var array
     */
    public $collection;

    /**
     * SearchResourceCollectionResponse constructor.
     * @param array $collection
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return mixed|void
     */
    public function jsonSerialize()
    {
        return $this->collection;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return empty($this->collection) ? 204 : 200;
    }
}
