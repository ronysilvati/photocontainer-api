<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class EventSearch
{
    private $id;
    private $photographer;
    private $title;
    private $eventdate;
    private $categories;
    private $tags;
    private $page;
    private $photos;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @var int
     */
    private $likes;

    /**
     * @var bool
     */
    private $publisherLike;

    /**
     * @var string
     */
    private $thumb;

    public function __construct(
        int $id = null,
        ?Photographer $photographer,
        ?string $title,
        ?array $categories,
        ?array $tags,
        int $page = 1)
    {
        $this->changeId($id);
        $this->changeTitle($title);
        $this->changePhotographer($photographer);
        $this->changeCategories($categories);
        $this->changeTags($tags);

        $this->page = $page;
    }

    /**
     * @return null|Photographer
     */
    public function getPhotographer(): ?Photographer
    {
        return $this->photographer;
    }

    /**
     * @param Photographer|null $photographer
     */
    public function changePhotographer(Photographer $photographer= null)
    {
        $this->photographer = $photographer;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function changeTitle(string $title = null)
    {
        $this->title = $title;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function changeId(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getEventdate(): ?string
    {
        return $this->eventdate;
    }

    /**
     * @param string|null $eventdate
     */
    public function changeEventdate(string $eventdate = null)
    {
        list($year, $month, $day) = explode("-", $eventdate);
        $this->eventdate = $day."/".$month."/".$year;
    }

    /**
     * @return array|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * @param array|null $categories
     */
    public function changeCategories(array $categories = null)
    {
        $this->categories = $categories;
    }

    /**
     * @return mixed
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function changeTags(?array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return mixed
     */
    public function getPhotos(): ?int
    {
        return $this->photos;
    }

    /**
     * @param mixed $photos
     */
    public function changePhotos(?int $photos)
    {
        $this->photos = $photos;
    }

    /**
     * @return int
     */
    public function getLikes(): ?int
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function changeLikes(?int $likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return Publisher
     */
    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    /**
     * @param Publisher $publisher
     */
    public function changePublisher(?Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @return bool
     */
    public function isPublisherLike(): bool
    {
        return $this->publisherLike == null ? false : $this->publisherLike;
    }

    /**
     * @param bool $publisherLike
     */
    public function changePublisherLike(bool $publisherLike)
    {
        $this->publisherLike = $publisherLike;
    }

    public function getSearchContext(): string
    {
        if ($this->publisher) {
            return "gallery_publisher";
        }

        if ($this->photographer) {
            return "gallery_photographer";
        }
    }

    /**
     * @return string
     */
    public function getThumb(): string
    {
        return "events/{$this->id}/thumb/{$this->thumb}";
    }

    /**
     * @param string $thumb
     */
    public function changeThumb(string $thumb)
    {
        $this->thumb = $thumb;
    }
}
