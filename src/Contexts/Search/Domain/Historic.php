<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Historic
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
     * @var bool
     */
    private $favorite;

    /**
     * @var bool
     */
    private $authorized;

    /**
     * Historic constructor.
     * @param int|null $photo_id
     * @param int $user_id
     * @param int $event_id
     * @param null|string $filename
     * @param bool|null $favorite
     * @param bool|null $authorized
     */
    public function __construct(
        ?int $photo_id,
        int $user_id,
        int $event_id,
        ?string $filename,
        ?bool $favorite,
        ?bool $authorized
    )
    {
        $this->photo_id = $photo_id;
        $this->user_id = $user_id;
        $this->event_id = $event_id;
        $this->filename = $filename;
        $this->favorite = $favorite;
        $this->authorized = $authorized;
    }

    /**
     * @return int
     */
    public function getPhotoId(): ?int
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
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @return bool
     */
    public function isFavorite(): ?bool
    {
        return $this->favorite ?? false;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): ?bool
    {
        return $this->authorized ?? false;
    }
}
