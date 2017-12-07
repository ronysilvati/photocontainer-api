<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Command;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventTag;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Suppliers;

class CreateEventCommand
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $user = new Photographer($data['user_id']);
        $suppliers =  new Suppliers(null, null, null);

        $allCategories = [];
        foreach ($data['categories'] as $category) {
            $allCategories[] = new EventCategory(null, $category);
        }

        $allTags = [];
        foreach ($data['tags'] as $tag) {
            $allTags[] = new EventTag(null, $tag);
        }

        $this->event = new Event(
            null,
            $user,
            $data['bride'],
            $data['groom'],
            $data['eventDate'],
            $data['title'],
            $data['description'],
            $data['country'],
            $data['state'],
            $data['city'],
            (bool) $data['terms'],
            (bool) $data['approval_general'],
            (bool) $data['approval_photographer'],
            (bool) $data['approval_bride'],
            $allCategories,
            $allTags,
            $suppliers
        );
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }
}