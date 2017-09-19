<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface PublisherPublicationRepository
{
    /**
     * @param PublisherPublication $publisherPublication
     * @return mixed
     */
    public function create(PublisherPublication $publisherPublication);
}