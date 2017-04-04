<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;

class EventFoundResponse implements \JsonSerializable
{
    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    function jsonSerialize()
    {
        $categories = [];
        foreach ($this->event->getCategories() as $item) {
            $categories[] = $item->getCategoryId();
        }

        $tags = [];
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
            "categories" => $categories,
            "tags" => $tags,
            "suppliers" => $this->event->getSuppliers()->getSuppliers(),
            "_links" => [
                "_self" => ['href' => "/events/{$this->event->getId()}"],
            ],
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