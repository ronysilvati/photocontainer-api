<?php
//error_reporting(E_ALL);
//ini_set("display_errors", true);

use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimApp;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\RestDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\BcryptHashing;
use PhotoContainer\PhotoContainer\Contexts\Event\EventContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\User\UserContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Auth\AuthContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Photo\PhotoContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Search\SearchContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Cep\CepContextBootstrap;

require '../vendor/autoload.php';

$app = new \Slim\App(['settings' => ['debug' => true, 'displayErrorDetails' => true,]]);
$container = $app->getContainer();

$container['DatabaseProvider'] = function ($c) {
    $database = new EloquentDatabaseProvider([
        'host'      => 'mysql',
        'database'  => 'photocontainer',
        'user'      => 'root',
        'pwd'       => 'root',
        'port'      => '3306',
    ]);
    return $database;
};

$container['CepRestProvider'] = function ($c) {
    $database = new RestDatabaseProvider([
        'host' => 'https://viacep.com.br/ws/',
    ]);
    return $database;
};

$container['CryptoMethod'] = function ($c) {
    return new BcryptHashing();
};

$webApp = new SlimApp($app);

$webApp->bootstrap(
    [
        'secret' => 'secret',
        "api_path" => ["/"],
        "auth_whitelist" => [
            "/login",
            "/users",
            "/events",
            "/search",
            "/event",
            "/location",
            "/photo",
        ],
    ]
);

$eventBoostrap = new AuthContextBootstrap();
$webApp = $eventBoostrap->wireSlimRoutes($webApp);

$eventBoostrap = new EventContextBootstrap();
$webApp = $eventBoostrap->wireSlimRoutes($webApp);

$eventBoostrap = new UserContextBootstrap();
$webApp = $eventBoostrap->wireSlimRoutes($webApp);

$eventBoostrap = new SearchContextBootstrap();
$webApp = $eventBoostrap->wireSlimRoutes($webApp);

$eventBoostrap = new CepContextBootstrap();
$webApp = $eventBoostrap->wireSlimRoutes($webApp);

$photoBoostrap = new PhotoContextBootstrap();
$webApp = $photoBoostrap->wireSlimRoutes($webApp);

$webApp->app->run();
