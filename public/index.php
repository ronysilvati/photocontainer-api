<?php

use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimApp;

define('ROOT_DIR', dirname(__DIR__));
define('CACHE_DIR', ROOT_DIR.'/cache');
define('LOG_DIR', ROOT_DIR.'/logs');
define('DEBUG_MODE', false);

require '../vendor/autoload.php';

if (is_file('.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->overload();
}

class SlimPHPDI extends \DI\Bridge\Slim\App
{
    protected function configureContainer(\DI\ContainerBuilder $builder)
    {
        $cache = new \Doctrine\Common\Cache\ApcuCache();
        $cache->setNamespace('PhotoContainer');
        $builder->setDefinitionCache($cache);

        $builder->addDefinitions('config.php');
    }
}

$app = new SlimPHPDI;

require 'routes.php';
require 'listeners.php';

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

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
            '/profile_images'
        ],
    ]
);

$webApp->app->run();