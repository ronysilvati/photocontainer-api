<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;


use PhotoContainer\PhotoContainer\Contexts\Photo\Event\DownloadedPhoto;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventRecorder;

class SelectedPhotos
{
    /**
     * @var array
     */
    private $photos;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var int
     */
    private $publisher_id;

    /**
     * @var int
     */
    private $event_id;

    /**
     * SelectedPhotos constructor.
     * @param int $publisher_id
     * @param int|null $event_id
     */
    public function __construct(int $publisher_id, ?int $event_id = null)
    {
        $this->photos = [];
        $this->publisher_id = $publisher_id;
        $this->event_id = $event_id;
    }

    /**
     * @param Photo $photo
     */
    public function add(Photo $photo): void
    {
        $this->photos[] = $photo;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @param string $zip
     */
    public function attachZip(string $zip): void
    {
        $this->zip = $zip;

        EventRecorder::getInstance()->record(
            new DownloadedPhoto($this->publisher_id, $this->photos[0]->getEventId(), $this->photos)
        );
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }
}