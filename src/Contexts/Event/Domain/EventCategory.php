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
    public function __construct(int $event_id = null, int $category_id = null)
    {
        $this->event_id = $event_id;
        $this->changeCategoryId($category_id);
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
    public function changeEventId(int $event_id): void
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
     * @throws \DomainException
     */
    public function changeCategoryId(?int $category_id = null): void
    {
        if ($category_id === null) {
            throw new \DomainException('A categoria é obrigatória.');
        }

        $this->category_id = $category_id;
    }
}
