<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Command\GetNotificationsCommand;
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

    public function handle(GetNotificationsCommand $command): NotificationResponse
    {
        $userId = $command->getUserId();

        $notification = new Notification();
        $notification
            ->addNotification('wait_list', $this->repository->approvalWaitList($userId))
            ->addNotification('event_notification', $this->repository->eventNotification($userId))
            ->addNotification('publisher_publications', $this->repository->publisherPublication($userId));

        return new NotificationResponse($notification);
    }
}
