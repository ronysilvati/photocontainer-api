<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface EventNotificationRepository
{
    /**
     * @param Event $event
     * @param Publisher $publisher
     * @return mixed
     */
    public function createNotification(Event $event, Publisher $publisher);
}