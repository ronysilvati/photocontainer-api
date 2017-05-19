<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventTag;

class EventFoundResponse implements \JsonSerializable
{
    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function jsonSerialize()
    {
        $categories = [];
        /** @var EventCategory $item */
        foreach ($this->event->getCategories() as $item) {
            $categories[] = $item->getCategoryId();
        }

        $tags = [];
        /** @var EventTag $item */
        foreach ($this->event->getTags() as $item) {
            $tags[] = $item->getTagId();
        }

        return [
            "id" => $this->event->getId(),
            "bride" => $this->event->getBride(),
            "groom" => $this->event->getGroom(),
            "photographer_id" => $this->event->getPhotographer()->getId(),
            "title" => $this->event->getTitle(),
            "description" => $this->event->getDescription(),
            "terms" => $this->event->getTerms(),
            "eventdate" => $this->event->getEventDate(),
            "approval_bride" => $this->event->getApprovalBride(),
            "approval_photographer" => $this->event->getApprovalPhotographer(),
            "approval" => $this->event->getApprovalGeneral(),
            "country" => $this->event->getCountry(),
            "city" => $this->event->getCity(),
            "state" => $this->event->getState(),
            "categories" => $categories,
            "tags" => $tags,
            "suppliers" => $this->event->getSuppliers()->getSuppliers(),
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
