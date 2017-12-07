<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Command;

class FindEventPhotosPhotographerCommand
{
    /**
     * @var int
     */
    private $photographerId;

    /**
     * FindEventPhotosPhotographerCommand constructor.
     * @param int $photographerId
     */
    public function __construct(int $photographerId)
    {
        $this->photographerId = $photographerId;
    }

    /**
     * @return int
     */
    public function getPhotographerId(): int
    {
        return $this->photographerId;
    }
}