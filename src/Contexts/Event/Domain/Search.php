<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class Search
{
    private $id;
    private $photographer;
    private $title;
    private $eventdate;

    public function __construct(int $id = null, string $photographer = null, string $title = null)
    {
        $this->changeId($id);
        $this->changeTitle($title);
        $this->changePhotographer($photographer);
    }

    /**
     * @return mixed
     */
    public function getPhotographer()
    {
        return $this->photographer;
    }

    /**
     * @param mixed $photographer
     */
    public function changePhotographer($photographer= null)
    {
        $this->photographer = $photographer;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function changeTitle($title = null)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function changeId($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEventdate()
    {
        return $this->eventdate;
    }

    /**
     * @param mixed $eventdate
     */
    public function changeEventdate($eventdate)
    {
        list($year, $month, $day) = explode("-", $eventdate);
        $this->eventdate = $day."/".$month."/".$year;
    }
}