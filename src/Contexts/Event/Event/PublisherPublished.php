<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Event;

use PhotoContainer\PhotoContainer\Infrastructure\Event\Event;

class PublisherPublished implements Event
{
    /**
     * @var int
     */
    private $event_id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var int
     */
    private $publisher_id;

    /**
     * PublisherPublished constructor.
     * @param int $event_id
     * @param string $text
     * @param int $publisher_id
     */
    public function __construct(int $event_id, string $text, int $publisher_id)
    {
        $this->event_id = $event_id;
        $this->text = $text;
        $this->publisher_id = $publisher_id;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getPublisherId(): int
    {
        return $this->publisher_id;
    }

    public function getName(): string
    {
        return 'publisher_published';
    }
}