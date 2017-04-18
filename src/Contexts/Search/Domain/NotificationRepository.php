<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

interface NotificationRepository
{
    public function approvalWaitList(int $photographer_id): int;
}
