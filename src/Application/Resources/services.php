<?php
$services = [];

$services['PhotoContainer\PhotoContainer\Contexts\*\Domain\*Repository'] = DI\object(
    'PhotoContainer\PhotoContainer\Contexts\*\Persistence\Eloquent*Repository'
);

$services['em'] = function (\Psr\Container\ContainerInterface $c) {
    $config = $c->get('database_config');
    $conn = new PDO("mysql:host={$config['MYSQL_HOST']}", $config['MYSQL_USER'], $config['MYSQL_PASSWORD']);

    $yamlConfig = \Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration(
        [__DIR__."/doctrine"],
        getenv('ENVIRONMENT')
    );
    return \Doctrine\ORM\EntityManager::create($conn, $yamlConfig);
};


return $services;