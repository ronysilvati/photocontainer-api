<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;



class Favorite
{
    private $id;
    private $publisher;
    private $event_id;

    /**
     * @var int
     */
    private $totalLikes;

    /**
     * Favorite constructor.
     * @param int|null $id
     * @param Publisher $publisher
     * @param int $event_id
     */
    public function __construct(?int $id, Publisher $publisher, int $event_id)
    {
        $this->changeEventId($event_id);
        $this->changeId($id);
        $this->changePublisher($publisher);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function changeId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Publisher
     */
    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }

    /**
     * @param Publisher $publisher
     */
    public function changePublisher(Publisher $publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * @return int|null
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @param int|null $event_id
     */
    public function changeEventId(int $event_id): void
    {
        $this->event_id = $event_id;
    }

    /**
     * @return int
     */
    public function getTotalLikes(): ?int
    {
        return $this->totalLikes;
    }

    /**
     * @param int|null $totalLikes
     */
    public function changeTotalLikes(?int $totalLikes): void
    {
        $this->totalLikes = $totalLikes;
    }
}
