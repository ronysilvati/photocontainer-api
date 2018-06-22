<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

class EventCollectionResponse implements \JsonSerializable
{
    private $httpStatus = 200;
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function jsonSerialize()
    {
        $out = ['total' => $this->collection['total'], 'result' => []];

        $formatData = function ($searchData) {
            foreach ($searchData as $search) {
                $data = [
                    'id' => $search->getId(),
                    'photographer' => $search->getPhotographer()->getName(),
                    'photographer_id' => $search->getPhotographer()->getId(),
                    'title' => $search->getTitle(),
                    'eventdate' => $search->getEventdate(),
                    'category' => $search->getCategories()[0]->getDescription(),
                    'thumb' => $search->getThumb() ?: 'sem-foto.png',
                    'watermark' => $search->getWatermark() ?: 'sem-foto.png',
                    'photos' => $search->getPhotos(),
                    'likes' => $search->getLikes(),
                    'context' => $search->getSearchContext(),
                ];

                if ($search->getPublisher()) {
                    $data['publisher_like'] = $search->isPublisherLike();
                }

                yield $data;
            }
        };

        foreach ($formatData($this->collection['result']) as $search) {
            $out['result'][] = $search;
        }

        return $out;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
