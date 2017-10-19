<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Publisher
{
    /**
     * @var int
     */
    private $id;

    /**
     * Publisher constructor.
     * @param int $id
     */
    public function __construct(?int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function changeId(?int $id): void
    {
        $this->id = $id;
    }
}
