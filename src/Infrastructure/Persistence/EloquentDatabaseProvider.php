<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence;

use Illuminate\Database\Capsule\Manager as Capsule;

class EloquentDatabaseProvider implements DatabaseProvider
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function boot(): void
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $this->config['host'],
            'port' => $this->config['port'],
            'database' => $this->config['database'],
            'username' => $this->config['user'],
            'password' => $this->config['pwd'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);
        $capsule->bootEloquent();
        $capsule->setAsGlobal();
    }
}
