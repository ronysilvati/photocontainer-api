<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Approval
{
    /**
     * @var int
     */
    private $photographer_id;

    /**
     * @var int
     */
    private $publisher_id;

    /**
     * @var string
     */
    private $created;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $event_id;

    /**
     * Approval constructor.
     * @param int $event_id
     * @param int $photographer_id
     * @param int $publisher_id
     * @param string $created
     * @param string $name
     */
    public function __construct(int $event_id, int $photographer_id, int $publisher_id, string $created, string $name)
    {
        $this->event_id = $event_id;
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

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }
}
