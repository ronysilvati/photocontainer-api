<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;

class DbalDatabaseProvider implements DatabaseProvider
{
    public $conn;
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

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
}