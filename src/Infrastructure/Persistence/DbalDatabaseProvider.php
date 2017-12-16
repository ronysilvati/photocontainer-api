<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;

class DbalDatabaseProvider implements DatabaseProvider
{
    /**
     * @var Connection
     */
    public $conn;

    /**
     * @var array
     */
    private $config;

    /**
     * DbalDatabaseProvider constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @throws DBALException
     */
    public function boot(): void
    {
        try {
            $config = new Configuration();

            $connectionParams = [
                'dbname' =>$this->config['database'],
                'user' => $this->config['user'],
                'password' => $this->config['pwd'],
                'host' => $this->config['host'],
                'port' => $this->config['port'],
                'driver' => 'pdo_mysql',
            ];

            $this->conn = DriverManager::getConnection($connectionParams, $config);
        } catch (DBALException $e) {
            throw $e;
        }
    }

    /**
     * @return Connection
     */
    public function getConn(): Connection
    {
        return $this->conn;
    }
}