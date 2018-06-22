<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;

class FavoriteCreatedResponse implements \JsonSerializable
{
    /**
     * @var int
     */
    private $httpStatus = 201;

    /**
     * @var Favorite
     */
    private $favorite;

    public function __construct(Favorite $favorite)
    {
        $this->favorite = $favorite;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->favorite->getId(),
            'event_id' => $this->favorite->getEventId(),
            'totalLikes' => $this->favorite->getTotalLikes(),
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
