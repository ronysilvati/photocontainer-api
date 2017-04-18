<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Approval
{
    private $photographer_id;
    private $publisher_id;
    private $created;
    private $name;

    /**
     * Approval constructor.
     * @param $photographer_id
     * @param $publisher_id
     * @param $created
     * @param $name
     */
    public function __construct(int $photographer_id, int $publisher_id, string $created, string $name)
    {
        $this->photographer_id = $photographer_id;
        $this->publisher_id = $publisher_id;
        $this->created = $created;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhotographerId(): int
    {
        return $this->photographer_id;
    }

    /**
     * @return mixed
     */
    public function getPublisherId(): int
    {
        return $this->publisher_id;
    }

    /**
     * @return mixed
     */
    public function getCreated(): string
    {
        $date = new \DateTime($this->created);
        return $date->format('d/m/Y');
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }
}
