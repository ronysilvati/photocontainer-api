<?php

use PhotoContainer\PhotoContainer\Contexts\Approval\ApprovalContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Auth\AuthContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Cep\CepContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Event\EventContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Photo\PhotoContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\Search\SearchContextBootstrap;
use PhotoContainer\PhotoContainer\Contexts\User\UserContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\BcryptHashing;
use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftMailerHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\RestDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\DbalDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimApp;
use PhotoContainer\PhotoContainer\Contexts\Contact\ContactContextBootstrap;

define('ROOT_DIR', dirname(__DIR__));
define('CACHE_DIR', ROOT_DIR.'/cache');
define('DEBUG_MODE', false);

require '../vendor/autoload.php';

if (is_file('.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}

$slimParams = [];
if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777);
}

if (!is_file(CACHE_DIR.'/routes.cache')) {
    $slimParams['settings'] = ['routerCacheFile' => CACHE_DIR.'/routes.cache'];
}

if (DEBUG_MODE) {
    $slimParams['settings'] = ['displayErrorDetails' => true];
}

$app = new \Slim\App($slimParams);
$container = $app->getContainer();

$container['DatabaseProvider'] = function ($c) {
    $database = new EloquentDatabaseProvider([
        'host'      => getenv('PHINX_DBHOST'),
        'database'  => getenv('PHINX_DBNAME'),
        'user'      => getenv('PHINX_DBUSER'),
        'pwd'       => getenv('PHINX_DBPASS'),
        'port'      => getenv('PHINX_DBPORT'),
    ]);

    $database->boot();
    return $database;
};

$container['DbalDatabaseProvider'] = function ($c) {
    $database = new DbalDatabaseProvider([
        'host'      => getenv('PHINX_DBHOST'),
        'database'  => getenv('PHINX_DBNAME'),
        'user'      => getenv('PHINX_DBUSER'),
        'pwd'       => getenv('PHINX_DBPASS'),
        'port'      => getenv('PHINX_DBPORT'),
    ]);

    $database->boot();
    return $database;
};

$container['CepRestProvider'] = function ($c) {
    $database = new RestDatabaseProvider([
        'host' => 'https://viacep.com.br/ws/',
    ]);

    $database->boot();
    return $database;
};

$container['CryptoMethod'] = function ($c) {
    return new BcryptHashing();
};

$container['EmailHelper'] = function ($c) {
    return new SwiftMailerHelper(Swift_SendmailTransport::newInstance('/usr/lib/sendmail -bs'));
};

$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
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
            '/contact'
        ],
    ]
);

$webApp->addContext(new AuthContextBootstrap())
    ->addContext(new EventContextBootstrap())
    ->addContext(new UserContextBootstrap())
    ->addContext(new SearchContextBootstrap())
    ->addContext(new CepContextBootstrap())
    ->addContext(new PhotoContextBootstrap())
    ->addContext(new ApprovalContextBootstrap())
    ->addContext(new ContactContextBootstrap());

$webApp->app->run();