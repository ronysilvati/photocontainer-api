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
        foreach ($this->collection['result'] as $search) {
            $data = [
                "id" => $search->getId(),
                "photographer" => $search->getPhotographer()->getName(),
                "photographer_id" => $search->getPhotographer()->getId(),
                "title" => $search->getTitle(),
                "eventdate" => $search->getEventdate(),
                "category" => $search->getCategories()[0]->getDescription(),
                'thumb' => $search->getThumb() == "" ? "sem-foto.png" : $search->getThumb(),
                "photos" => $search->getPhotos(),
                "likes" => $search->getLikes(),
                "context" => $search->getSearchContext(),
                "_links" => [
                    "_self" => ['href' => '/events/'.$search->getId()],
                ],
            ];

            if ($search->getPublisher()) {
                $data['publisher_like'] = $search->isPublisherLike();
            }

            $out['result'][] = $data;
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
