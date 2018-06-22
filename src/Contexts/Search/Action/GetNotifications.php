<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Notification;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\NotificationRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\NotificationResponse;

class GetNotifications
{
    protected $repository;

    public function __construct(NotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $user_id): \PhotoContainer\PhotoContainer\Contexts\Search\Response\NotificationResponse
    {
        $notification = new Notification();
        $notification->addNotification('wait_list', $this->repository->approvalWaitList($user_id));
        $notification->addNotification('event_notification', $this->repository->eventNotification($user_id));
        $notification->addNotification('publisher_publications', $this->repository->publisherPublication($user_id));

        return new NotificationResponse($notification);
    }
}
