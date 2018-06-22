<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

use PhotoContainer\PhotoContainer\Contexts\Photo\Event\DownloadedPhoto;

use PhotoContainer\PhotoContainer\Infrastructure\Event\EventRecorder;

class Download
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $user_id;

    /**
     * @var Photo
     */
    private $photo;

    /**
     * Download constructor.
     * @param int|null $id
     * @param int $user_id
     * @param Photo $photo
     */
    public function __construct(?int $id, int $user_id, Photo $photo)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->photo = $photo;

        EventRecorder::getInstance()
            ->record(new DownloadedPhoto($user_id, $photo->getEventId(), [$photo]));
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
    public function changeId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @return Photo
     */
    public function getPhoto(): Photo
    {
        return $this->photo;
    }
}
