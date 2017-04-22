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
use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimApp;

require '../vendor/autoload.php';

define('ROOT_DIR', dirname(__DIR__));
define('CACHE_DIR', ROOT_DIR.'/cache');

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

$approvalBootstrap = new ApprovalContextBootstrap();
$webApp = $approvalBootstrap->wireSlimRoutes($webApp);

$webApp->app->run();