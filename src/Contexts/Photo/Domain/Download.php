<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

use PhotoContainer\PhotoContainer\Infrastructure\Entity;

class Download implements Entity
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
    public function changeId(int $id)
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