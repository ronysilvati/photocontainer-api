<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;

class FavoriteRemovedResponse implements \JsonSerializable
{
    private $favorite;
    private $selfReference;

    public function __construct(Favorite $favorite)
    {
        $this->favorite = $favorite;
        $this->selfReference = "event/{$this->favorite->getEventId()}/favorite/{$this->favorite->getId()}";
    }

    public function jsonSerialize()
    {
        return [
            'event_id' => $this->favorite->getEventId(),
            'totalLikes' => $this->favorite->getTotalLikes(),
            '_links' => [
                '_self' => ['href' => $this->selfReference],
            ],
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 201;
    }
}
