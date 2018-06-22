<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence;

use GuzzleHttp\Client;

class RestDatabaseProvider implements DatabaseProvider
{
    private $config;
    public $client;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function boot(): void
    {
        $this->client = new Client([
            'base_uri' => $this->config['host'],
            'timeout'  => 5.0,
        ]);
    }
}
