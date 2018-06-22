<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\PublisherPublication;

class PublisherPublicationResponse implements \JsonSerializable
{
    /**
     * @var PublisherPublication
     */
    private $publisherPublication;

    /**
     * PublisherPublicationResponse constructor.
     * @param PublisherPublication $publisherPublication
     */
    public function __construct(PublisherPublication $publisherPublication)
    {
        $this->publisherPublication = $publisherPublication;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->publisherPublication->getId(),
        ];
    }

    public function getHttpStatus()
    {
        return 201;
    }
}