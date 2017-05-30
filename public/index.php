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
use PhotoContainer\PhotoContainer\Infrastructure\Event\EvenementEventProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentAtomicWorker;

define('ROOT_DIR', dirname(__DIR__));
define('CACHE_DIR', ROOT_DIR.'/cache');
define('LOG_DIR', ROOT_DIR.'/logs');
define('DEBUG_MODE', false);

require '../vendor/autoload.php';

if (is_file('.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->overload();
}

$slimParams = [
    'settings' => ['determineRouteBeforeAppMiddleware' => true]
];
if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777);
}

if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR, 0777);
}

if (!is_file(CACHE_DIR.'/routes.cache')) {
    $slimParams['settings'] = ['routerCacheFile' => CACHE_DIR.'/routes.cache'];
}

if (DEBUG_MODE) {
    $slimParams['settings'] = [
        'displayErrorDetails' => true,
        'debug' => true,
    ];
}

$app = new \Slim\App($slimParams);

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('API_LOG');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/api.log");
    $logger->pushHandler($file_handler);
    return $logger;
} ;

$container['errorHandler'] = function ($c) {
    return function (\Psr\Http\Message\ServerRequestInterface $request, $response, Exception $e) use ($c) {
        $trace = $e->getTrace();

        $data = [
            'file' => $e->getFile() . ': '. $e->getLine(),
            'route' => $request->getMethod(). ' ' . $request->getUri()->getPath(),
            'actionClass' => $trace[0],
            'contextClass' => $trace[1],
        ];

        $body = $request->getParsedBody();
        if (!empty($body)) {
            $data['payload'] = $body;
        }

        $message = get_class($e) == PersistenceException::class ? $e->getInfraLayerError() : $e->getMessage();

        $c->logger->addCritical($message, $data);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, X-Requested-With, Content-Type, Accept, Origin, Authorization, Cache-Control')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
            ->withHeader('Access-Control-Max-Age', '604800')
            ->withJson(['message' => $e->getMessage()], 500);
    };
};

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

$container['AtomicWorker'] = function ($c) {
    return new EloquentAtomicWorker();
};

$container['EmailHelper'] = function ($c) {
    return new SwiftMailerHelper(Swift_SendmailTransport::newInstance('/usr/lib/sendmail -bs'));
};

$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};

$container['EventEmitter'] = function () {
    return new EvenementEventProvider();
};

$webApp = new SlimApp($app);

$webApp
    ->addContext(AuthContextBootstrap::class)
    ->addContext(EventContextBootstrap::class)
    ->addContext(UserContextBootstrap::class)
    ->addContext(SearchContextBootstrap::class)
    ->addContext(CepContextBootstrap::class)
    ->addContext(PhotoContextBootstrap::class)
    ->addContext(ApprovalContextBootstrap::class)
    ->addContext(ContactContextBootstrap::class);

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


$webApp->app->run();