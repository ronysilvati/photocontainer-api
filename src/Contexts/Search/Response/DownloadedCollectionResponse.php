<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

class DownloadedCollectionResponse implements \JsonSerializable
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
            $out['photos'][] = [
                'event_id' => $item->getEventId(),
                'user_id' => $item->getUserId(),
                'filename' => $item->getFilename(),
                'photo_id' => $item->getPhotoId(),
                'context' => 'gallery_publisher_downloads',
            ];
        }

        return $out;
    }

    public function getHttpStatus()
    {
        return 200;
    }
}