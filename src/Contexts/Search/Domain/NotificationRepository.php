<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

interface NotificationRepository
{
    /**
     * @param int $photographer_id
     * @return int
     */
    public function approvalWaitList(int $photographer_id): int;

    /**
     * @param int $publisher_id
     * @return int
     */
    public function eventNotification(int $publisher_id): int;

    /**
     * @param int $photographer_id
     * @return int
     */
    public function publisherPublication(int $photographer_id): int;
}
