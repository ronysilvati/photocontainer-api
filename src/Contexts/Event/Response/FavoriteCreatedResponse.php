<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;

class FavoriteCreatedResponse implements \JsonSerializable
{
    private $httpStatus = 201;
    private $favorite;

    public function __construct(Favorite $favorite)
    {
        $this->favorite = $favorite;
        $this->selfReference = "event/{$this->favorite->getEventId()}/favorite/{$this->favorite->getId()}";
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->favorite->getId(),
            'event_id' => $this->favorite->getEventId(),
            "totalLikes" => $this->favorite->getTotalLikes(),
            "_links" => [
                "_self" => ['href' => $this->selfReference],
            ],
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
