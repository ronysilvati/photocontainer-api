<?php

use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimApp;

define('ROOT_DIR', dirname(__DIR__));
define('CACHE_DIR', ROOT_DIR.'/var/cache');
define('LOG_DIR', ROOT_DIR.'/var/logs');
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
        if (getenv('ENVIRONMENT') === 'prod') {
            $cache = new \Doctrine\Common\Cache\ApcuCache();
        } else {
            $cache = new \Doctrine\Common\Cache\ArrayCache();
        }

        $cache->setNamespace('PhotoContainer');
        $builder->setDefinitionCache($cache);

        $builder->addDefinitions('slim_config.php');
        $builder->addDefinitions('config.php');
        $builder->addDefinitions('../src/Application/Resources/services.php');

        return $builder;
    }
}

$app = new SlimPHPDI;

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