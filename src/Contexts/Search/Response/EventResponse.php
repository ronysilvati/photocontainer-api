<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Event;

class EventResponse implements \JsonSerializable
{
    /**
     * @var Event
     */
    private $event;

    public function __construct(Event $event, string $context)
    {
        $this->event = $event;
        $this->context = $context;
    }

    public function jsonSerialize()
    {
        if ($this->context == 'gallery_photos_publisher') {
            return [
                'id' => $this->event->getId(),
                'authorized' => $this->event->isApprovedForPublisher(),
                'title' => $this->event->getTitle(),
                'photographer' => $this->event->getPhotographer(),
                'category' => $this->event->getCategory(),
                'photos' => $this->event->getPhotos(),
                'thumb' => '/user/themes/photo-container-site/_temp/photos/1.jpg',
                'context' => $this->context
            ];
        }

        if ($this->context == 'gallery_photos_photographer') {
            return [
                'id' => $this->event->getId(),
                'title' => $this->event->getTitle(),
                'photographer' => $this->event->getPhotographer(),
                'category' => $this->event->getCategory(),
                'photos' => $this->event->getPhotos(),
                'context' => $this->context
            ];
        }
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
