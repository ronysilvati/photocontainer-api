<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Approval;

class ApprovalCollectionResponse implements \JsonSerializable
{
    private $collection;

    /**
     * ApprovalCollectionResponse constructor.
     * @param Approval[] $collection
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $out = [];
        foreach ($this->collection as $item) {
            $out[] = [
                'event_id' => $item->getEventId(),
                'photographer_id' => $item->getPhotographerId(),
                'publisher_id' => $item->getPublisherId(),
                'name' => $item->getName(),
                'created' => $item->getCreated(),
                'publisher_name' => $item->getPublisherName(),
                'blog' => $item->getBlog(),
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
