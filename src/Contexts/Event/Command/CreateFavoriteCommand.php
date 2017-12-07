<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Command;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;

class CreateFavoriteCommand
{
    /**
     * @var Favorite
     */
    private $favorite;

    /**
     * CreateFavoriteCommand constructor.
     * @param int $publisherId
     * @param int $eventId
     */
    public function __construct(int $publisherId, int $eventId)
    {
        $this->favorite = new Favorite(
            null,
            new Publisher($publisherId, null, null),
            $eventId
        );
    }

    /**
     * @return Favorite
     */
    public function getFavorite(): Favorite
    {
        return $this->favorite;
    }
}