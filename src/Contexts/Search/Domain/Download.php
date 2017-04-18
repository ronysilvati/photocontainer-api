<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Download
{
    /**
     * @var int
     */
    private $photo_id;

    /**
     * @var int
     */
    private $user_id;

    /**
     * @var int
     */
    private $event_id;

    /**
     * @var string
     */
    private $filename;

    /**
     * Download constructor.
     * @param int $photo_id
     * @param int $user_id
     * @param int $event_id
     * @param string $filename
     */
    public function __construct(int $photo_id, int $user_id, int $event_id, string $filename)
    {
        $this->photo_id = $photo_id;
        $this->user_id = $user_id;
        $this->event_id = $event_id;
        $this->filename = $filename;
    }

    /**
     * @return int
     */
    public function getPhotoId(): int
    {
        return $this->photo_id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
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
    public function getFilename(): string
    {
        return $this->filename;
    }
}
