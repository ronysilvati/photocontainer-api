<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Command;

class DownloadPhotoCommand
{
    /**
     * @var int
     */
    private $photoId;

    /**
     * @var int
     */
    private $userId;

    /**
     * DownloadPhotoCommand constructor.
     * @param int $photoId
     * @param int $userId
     */
    public function __construct(int $photoId, int $userId)
    {
        $this->photoId = $photoId;
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getPhotoId(): int
    {
        return $this->photoId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}