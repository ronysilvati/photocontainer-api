<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

class HistoricCollectionResponse implements \JsonSerializable
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function jsonSerialize()
    {
        $out = ['photos' => []];

        foreach ($this->collection as $item) {
            $out['photos'][] = [
                'id' => $item->getPhotoId(),
                'event_id' => $item->getEventId(),
                'user_id' => $item->getUserId(),
                'filename' => $item->getFilename(),
                'photo_id' => $item->getPhotoId(),
                'thumb' => "events/{$item->getEventId()}/thumb/{$item->getFilename()}",
                'context' => 'gallery_publisher_historic',
                'liked' => $item->isFavorite(),
            ];
        }

        return $out;
    }

    public function getHttpStatus()
    {
        return 200;
    }
}
