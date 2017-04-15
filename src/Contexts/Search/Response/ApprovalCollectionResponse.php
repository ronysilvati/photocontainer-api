<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

class ApprovalCollectionResponse implements \JsonSerializable
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    function jsonSerialize()
    {
        $out = [];
        foreach ($this->collection as $item) {

            $out[] = [
                "photographer_id" => $item->getPhotographerId(),
                "publisher_id" => $item->getPublisherId(),
                "name" => $item->getName(),
                "created" => $item->getCreated(),
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