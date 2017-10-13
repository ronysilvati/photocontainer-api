<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventNotificationRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventNotification as EventNotificationModel;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventNotification;

class EloquentEventNotificationRepository implements EventNotificationRepository
{
    /**
     * @inheritdoc
     */
    public function createNotification(Event $event, Publisher $publisher)
    {
        $notification = new EventNotificationModel();
        $notification->publisher_id = $publisher->getId();
        $notification->event_id = $event->getId();
        $notification->visualized = true;

        $notification->save();

        return new EventNotification($event, $publisher);
    }
}