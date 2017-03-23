<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;


class EventCategory
{
    public $event_id;
    public $category_id;

    /**
     * Category constructor.
     * @param $event_id
     * @param $category_id
     */
    public function __construct(int $event_id = null, int $category_id)
    {
        $this->event_id = $event_id;
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @param mixed $event_id
     */
    public function changeEventId(int $event_id)
    {
        $this->event_id = $event_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function changeCategoryId(int $category_id)
    {
        $this->category_id = $category_id;
    }


}