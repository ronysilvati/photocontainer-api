<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class Search
{
    private $id;
    private $photographer;
    private $title;
    private $eventdate;
    private $categories;

    public function __construct(
        int $id = null,
        string $photographer = null,
        string $title = null,
        array $categories = null)
    {
        $this->changeId($id);
        $this->changeTitle($title);
        $this->changePhotographer($photographer);
    }

    /**
     * @return Photographer
     */
    public function getPhotographer(): ?string
    {
        return $this->photographer;
    }

    /**
     * @param Photographer|null $photographer
     */
    public function changePhotographer(string $photographer= null)
    {
        $this->photographer = $photographer;
    }

    /**
     * @return string
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
     * @return int
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
     * @return string
     */
    public function getEventdate(): ?string
    {
        return $this->eventdate;
    }

    /**
     * @param string $eventdate
     */
    public function changeEventdate(string $eventdate = null)
    {
        list($year, $month, $day) = explode("-", $eventdate);
        $this->eventdate = $day."/".$month."/".$year;
    }

    /**
     * @return array
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories = null)
    {
        $this->categories = $categories;
    }
}