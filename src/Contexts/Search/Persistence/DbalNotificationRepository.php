<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\NotificationRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;

use PhotoContainer\PhotoContainer\Infrastructure\Persistence\DbalDatabaseProvider;

class DbalNotificationRepository implements NotificationRepository
{
    private $conn;

    /**
     * DbalNotificationRepository constructor.
     * @param DbalDatabaseProvider $provider
     */
    public function __construct(DbalDatabaseProvider $provider)
    {
        $this->conn = $provider->conn;
    }

    public function approvalWaitList(int $photographer_id): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM event_search_approvals WHERE visualized = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $photographer_id);
            $stmt->execute();
            $result = $stmt->fetch();

            return $result['total'];
        } catch (\Exception $e) {
            throw new PersistenceException(
                'Não foi possível recuperar a contagem para esse tipo de notificação.',
                $e->getMessage()
            );
        }
    }
}