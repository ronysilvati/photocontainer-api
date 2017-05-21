<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Response;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Event;

class EventResponse implements \JsonSerializable
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var string
     */
    private $context;

    public function __construct(Event $event, string $context)
    {
        $this->event = $event;
        $this->context = $context;
    }

    public function jsonSerialize()
    {
        $out = [];
        if ($this->context == 'gallery_photos_publisher') {
            $thumb = !empty($this->event->getPhotos()) ? $this->event->getPhotos()[0]['thumb'] : 'sem_foto.png';

            $out = [
                'id' => $this->event->getId(),
                'authorized' => $this->event->isApprovedForPublisher(),
                'title' => $this->event->getTitle(),
                'photographer' => $this->event->getPhotographer(),
                'category' => $this->event->getCategory(),
                'photos' => $this->event->getPhotos(),
                'thumb' => $thumb,
                'context' => $this->context
            ];
        }

        if ($this->context == 'gallery_photos_photographer') {
            $out = [
                'id' => $this->event->getId(),
                'title' => $this->event->getTitle(),
                'photographer' => $this->event->getPhotographer(),
                'category' => $this->event->getCategory(),
                'photos' => $this->event->getPhotos(),
                'context' => $this->context
            ];
        }

        return $out;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
