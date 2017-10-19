<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Event
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $photographer;

    /**
     * @var string
     */
    private $category;

    /**
     * @var array
     */
    private $photos;

    /**
     * @var bool
     */
    private $approvedForPublisher;

    /**
     * Event constructor.
     * @param int $id
     * @param string $title
     * @param string $photographer
     * @param string $category
     * @param array $photos
     */
    public function __construct(int $id, string $title, string $photographer, string $category, array $photos)
    {
        $this->id = $id;
        $this->title = $title;
        $this->photographer = $photographer;
        $this->category = $category;
        $this->photos = $photos;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getPhotographer(): string
    {
        return $this->photographer;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @return bool
     */
    public function isApprovedForPublisher(): bool
    {
        return $this->approvedForPublisher;
    }

    /**
     * @param bool $approvedForPublisher
     */
    public function changeApprovedForPublisher(bool $approvedForPublisher): void
    {
        $this->approvedForPublisher = $approvedForPublisher;
    }
}
