<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;


class Event
{
    private $id;
    private $bride;
    private $groom;
    private $eventDate;
    private $title;
    private $description;
    private $terms;
    private $approval_general;
    private $approval_photographer;
    private $approval_bride;
    private $photographer;

    public function __construct(int $id = null,
                                Photographer $photographer,
                                string $bride,
                                string $groom = null,
                                string $eventDate = null,
                                string $title,
                                string $description = null,
                                bool $terms = null,
                                bool $approval_general = null,
                                bool $approval_photographer = null,
                                bool $approval_bride = null)
    {
        $this->changeTitle($title);
        $this->changeBride($bride);
        $this->changeGroom($groom);
        $this->changeTitle($title);
        $this->changeDescription($description);
        $this->changeEventDate($eventDate);
        $this->changeTerms($terms);
        $this->changeApprovalGeneral($approval_general);
        $this->changeApprovalBride($approval_bride);
        $this->changeApprovalPhotographer($approval_photographer);
        $this->changePhotographer($photographer);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function changeId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getBride()
    {
        return $this->bride;
    }

    /**
     * @param mixed $bride
     */
    public function changeBride(string $bride)
    {
        $this->bride = $bride;
    }

    /**
     * @return mixed
     */
    public function getGroom()
    {
        return $this->groom;
    }

    /**
     * @param mixed $groom
     */
    public function changeGroom(string $groom)
    {
        $this->groom = $groom;
    }

    /**
     * @return mixed
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * @param mixed $eventDate
     */
    public function changeEventDate(string $eventDate)
    {
        $this->eventDate = $eventDate;
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
    public function changeTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function changeDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * @param mixed $terms
     */
    public function changeTerms(bool $terms)
    {
        $this->terms = $terms;
    }

    /**
     * @return mixed
     */
    public function getApprovalGeneral()
    {
        return $this->approval_general;
    }

    /**
     * @param mixed $approval_general
     */
    public function changeApprovalGeneral(bool $approval_general)
    {
        $this->approval_general = $approval_general;
    }

    /**
     * @return mixed
     */
    public function getApprovalPhotographer()
    {
        return $this->approval_photographer;
    }

    /**
     * @param mixed $approval_photographer
     */
    public function changeApprovalPhotographer(bool $approval_photographer)
    {
        $this->approval_photographer = $approval_photographer;
    }

    /**
     * @return mixed
     */
    public function getApprovalBride()
    {
        return $this->approval_bride;
    }

    /**
     * @param mixed $approval_bride
     */
    public function changeApprovalBride(bool $approval_bride)
    {
        $this->approval_bride = $approval_bride;
    }

    /**
     * @return mixed
     */
    public function getPhotographer(): Photographer
    {
        return $this->photographer;
    }

    /**
     * @param mixed $photographer
     */
    public function changePhotographer($photographer)
    {
        $this->photographer = $photographer;
    }


}