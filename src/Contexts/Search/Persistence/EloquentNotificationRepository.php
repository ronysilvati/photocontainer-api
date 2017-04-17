<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\NotificationRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearchApproval;

class EloquentNotificationRepository implements NotificationRepository
{
    public function approvalWaitList(int $photographer_id): int
    {
        try {
            return EventSearchApproval::where('photographer_id', $photographer_id)
                ->where('visualized', 0)
                ->count();
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível recuperar a contagem para esse tipo de notificação.');
        }
    }
}
