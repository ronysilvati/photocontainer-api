<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Event;

class EventResponse implements \JsonSerializable
{
    /**
     * @var Event
     */
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    function jsonSerialize()
    {
        return [
            'id' => $this->event->getId(),
            'title' => $this->event->getTitle(),
            'photographer' => $this->event->getPhotographer(),
            'category' => $this->event->getCategory(),
            'photos' => $this->event->getPhotos(),
            'thumb' => 'http://192.168.99.100/user/themes/photo-container-site/_temp/photos/1.jpg',
            'context' => 'gallery_photos_publisher'
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