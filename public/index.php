<?php

define('ROOT_DIR', dirname(__DIR__));
define('CACHE_DIR', ROOT_DIR.'/var/cache');
define('LOG_DIR', ROOT_DIR.'/var/logs');
define('DEBUG_MODE', false);

require '../vendor/autoload.php';

use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimApp;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimPHPDI;
use Dotenv\Dotenv;

if (is_file('.env')) {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->overload();
}

$app = new SlimPHPDI();

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
            '/contact',
            '/profile_images',
            '/purge'
        ],
    ]
);

$webApp->app->run();