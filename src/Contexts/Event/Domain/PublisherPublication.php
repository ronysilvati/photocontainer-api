<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class PublisherPublication
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $event_id;

    /**
     * @var int
     */
    private $publisher_id;

    /**
     * @var int
     */
    private $askForChanges;

    /**
     * @var int
     */
    private $approved;

    /**
     * @var string
     */
    private $text;

    /**
     * PublisherPublication constructor.
     * @param int|null $id
     * @param int $event_id
     * @param int $publisher_id
     * @param string $text
     * @param bool $askForChanges
     * @param bool $approved
     */
    public function __construct(
        ?int $id = null,
        int $event_id,
        int $publisher_id,
        string $text,
        bool $askForChanges = false,
        bool $approved = false
    ) {
        $this->event_id = $event_id;
        $this->publisher_id = $publisher_id;
        $this->askForChanges = $askForChanges;
        $this->approved = $approved;
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @return int
     */
    public function getPublisherId(): int
    {
        return $this->publisher_id;
    }

    /**
     * @return int
     */
    public function getAskForChanges(): int
    {
        return $this->askForChanges;
    }

    /**
     * @return int
     */
    public function getApproved(): int
    {
        return $this->approved;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}