<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

class EmailDataLoader
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * EmailDataLoader constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->connection = $em->getConnection();
    }

    /**
     * @param int $userId
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUserData(int $userId): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users u, user_details a WHERE u.id = a.user_id AND u.id = ?'
        );
        $statement->bindValue(1, $userId);

        $statement->execute();
        return (array) $statement->fetch();
    }

    /**
     * @param int $eventId
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getEventData(int $eventId): array
    {
        $statement = $this->connection->prepare('SELECT * FROM events WHERE id = ?');
        $statement->bindValue(1, $eventId);

        $statement->execute();
        return (array) $statement->fetch();
    }
}