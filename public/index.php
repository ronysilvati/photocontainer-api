<?php
error_reporting(E_ALL);
ini_set("display_errors", true);

use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimApp;
use PhotoContainer\PhotoContainer\Contexts\User\Persistence\EloquentUserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\BcryptHashing;
use PhotoContainer\PhotoContainer\Contexts\Event\EventContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\User\UserContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Event\AuthContextBootstrap;

require '../vendor/autoload.php';

$app = new \Slim\App();
$container = $app->getContainer();

$container['DatabaseProvider'] = function ($c) {
    $database = new EloquentDatabaseProvider([
        'host' => '192.168.99.100',
        'port' => '32706',
        'database' => 'photocontainer',
        'user' => 'photocontainer',
        'pwd' => '1234',
    ]);
    return $database;
};

$container['EloquentUserRepository'] = function ($c) {
    return new EloquentUserRepository();
};

$container['CryptoMethod'] = function ($c) {
    return new BcryptHashing();
};

$webApp = new SlimApp($app);

$webApp->bootstrap(
    [
        'secret' => 'secret',
        "api_path" => ["/"],
        "auth_whitelist" => ["/login", "/users", "/events"],
    ]
);

$eventBoostrap = new \PhotoContainer\PhotoContainer\Contexts\Auth\AuthContextBootstrap();
$webApp = $eventBoostrap->wireSlimRoutes($webApp);

$eventBoostrap = new EventContextBootstrap();
$webApp = $eventBoostrap->wireSlimRoutes($webApp);

$eventBoostrap = new UserContextBootstrap();
$webApp = $eventBoostrap->wireSlimRoutes($webApp);

$webApp->app->run();