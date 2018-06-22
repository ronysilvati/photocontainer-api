<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

class Like
{
    /**
     * @var int
     */
    private $user_id;

    /**
     * @var int
     */
    private $photo_id;

    /**
     * Like constructor.
     * @param int $user_id
     * @param int $photo_id
     */
    public function __construct(int $user_id, int $photo_id)
    {
        $this->user_id = $user_id;
        $this->photo_id = $photo_id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function changeUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getPhotoId(): int
    {
        return $this->photo_id;
    }

    /**
     * @param int $photo_id
     */
    public function changePhotoId(int $photo_id): void
    {
        $this->photo_id = $photo_id;
    }
}
