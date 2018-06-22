<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Event;


use PhotoContainer\PhotoContainer\Infrastructure\Event\Event;

class DownloadedPhoto implements Event
{
    /**
     * @var int
     */
    private $publisher_id;

    /**
     * @var int
     */
    private $event_id;

    /**
     * @var array
     */
    private $photos;

    /**
     * DownloadedPhoto constructor.
     * @param int $publisher_id
     * @param int|null $event_id
     * @param array $photos
     */
    public function __construct(int $publisher_id, ?int $event_id, array $photos)
    {
        $this->publisher_id = $publisher_id;
        $this->event_id = $event_id;
        $this->photos = $photos;
    }

    /**
     * @return int
     */
    public function getPublisherId(): int
    {
        return $this->publisher_id;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function getName(): string
    {
        return 'downloaded_photo';
    }
}