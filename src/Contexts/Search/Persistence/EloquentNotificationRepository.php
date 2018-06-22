<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use Illuminate\Database\Capsule\Manager as DB;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\NotificationRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;


class EloquentNotificationRepository implements NotificationRepository
{
    /**
     * @inheritdoc
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function approvalWaitList(int $photographer_id): int
    {
        try {
            $results = DB::select(
                'select * from event_search_approvals where photographer_id = ? AND visualized = 0',
                [$photographer_id]
            );

            return count($results);
        } catch (\Exception $e) {
            throw new PersistenceException(
                'Não foi possível recuperar a contagem para esse tipo de notificação.',
                $e->getMessage()
            );
        }
    }

    public function eventNotification(int $publisher_id): int
    {
        $results = DB::select(
            'select * from event_notifications where publisher_id = ? AND approved = 0 AND visualized = 0',
            [$publisher_id]
        );

        return count($results);
    }

    public function publisherPublication(int $publisher_id): int
    {
        $results = DB::select(
            'SELECT *
             FROM publisher_publications pp
                  INNER JOIN events e
                    ON e.id = pp.event_id	
            WHERE user_id = ? AND approved = 1 AND visualized = 0',
            [$publisher_id]
        );

        return count($results);
    }
}
