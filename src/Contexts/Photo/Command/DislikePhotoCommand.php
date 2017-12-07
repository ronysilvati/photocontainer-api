<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Command;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;

class DislikePhotoCommand
{
    /**
     * @var int
     */
    private $photoId;

    /**
     * @var int
     */
    private $publisherId;

    /**
     * @var Like
     */
    private $like;

    /**
     * LikePhotoCommand constructor.
     * @param int $photoId
     * @param int $publisherId
     */
    public function __construct(int $photoId, int $publisherId)
    {
        $this->photoId = $photoId;
        $this->publisherId = $publisherId;
        $this->like = new Like($publisherId, $photoId);
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
    public function getPublisherId(): int
    {
        return $this->publisherId;
    }

    /**
     * @return Like
     */
    public function getLike(): Like
    {
        return $this->like;
    }
}