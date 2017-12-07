<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Command;

class CreatePublisherPublicationCommand
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * @var int
     */
    private $publisherId;

    /**
     * @var string
     */
    private $text;

    /**
     * @var bool
     */
    private $askForChanges;

    /**
     * @var bool
     */
    private $approved;

    /**
     * CreatePublisherPublicationCommand constructor.
     * @param int $eventId
     * @param int $publisherId
     * @param string $text
     * @param bool $askForChange
     * @param bool $approved
     */
    public function __construct(int $eventId, int $publisherId, string $text, bool $askForChange, bool $approved)
    {
        $this->eventId = $eventId;
        $this->publisherId = $publisherId;
        $this->text = $text;
        $this->askForChanges = $askForChange;
        $this->approved = $approved;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * @return int
     */
    public function getPublisherId(): int
    {
        return $this->publisherId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return bool
     */
    public function isAskForChanges(): bool
    {
        return $this->askForChanges;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }
}