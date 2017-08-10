<?php
$slimParams = [
    'settings.responseChunkSize' => 4096,
    'settings.outputBuffering' => 'append',
    'settings.determineRouteBeforeAppMiddleware' => false,
    'settings.displayErrorDetails' => false,
];

if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777);
}

if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR, 0777);
}

if (!is_file(CACHE_DIR.'/routes.cache')) {
    $slimParams = ['settings.routerCacheFile' => CACHE_DIR.'/routes.cache'];
}

if (DEBUG_MODE) {
    $slimParams = [
        'settings.displayErrorDetails' => true,
        'settings.debug' => true,
    ];
}

$slimParams['settings.logger'] = function($c) {
    $logger = new \Monolog\Logger('API_LOG');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/api.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$slimParams['settings.errorHandler'] = function ($c) {
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

        $message = get_class($e) == \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException::class ? $e->getInfraLayerError() : $e->getMessage();

        $c->get('logger')->addCritical($message, $data);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, X-Requested-With, Content-Type, Accept, Origin, Authorization, Cache-Control, Expires')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
            ->withHeader('Access-Control-Max-Age', '604800')
            ->withJson(['message' => $e->getMessage()], 500);
    };
};

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider::class] = function ($c) {
    $database = new \PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider([
        'host'      => getenv('MYSQL_HOST'),
        'database'  => getenv('MYSQL_DATABASE'),
        'user'      => getenv('MYSQL_USER'),
        'pwd'       => getenv('MYSQL_PASSWORD'),
        'port'      => getenv('MYSQL_PORT'),
    ]);

    $database->boot();
    return $database;
};

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Persistence\DbalDatabaseProvider::class] = function ($c) {
    $database = new \PhotoContainer\PhotoContainer\Infrastructure\Persistence\DbalDatabaseProvider([
        'host'      => getenv('MYSQL_HOST'),
        'database'  => getenv('MYSQL_DATABASE'),
        'user'      => getenv('MYSQL_USER'),
        'pwd'       => getenv('MYSQL_PASSWORD'),
        'port'      => getenv('MYSQL_PORT'),
    ]);

    $database->boot();
    return $database;
};

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Crypto\BcryptHashing();
};

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Persistence\AtomicWorker::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentAtomicWorker();
};

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Helper\TokenGeneratorHelper::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Helper\TokenGeneratorHelper();
};

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Helper\ProfileImageHelper::class] = DI\object(
    PhotoContainer\PhotoContainer\Infrastructure\Helper\ProfileImageHelper::class
);

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftMailerHelper(
        new Swift_SendmailTransport('/usr/lib/sendmail -bs')
    );
};

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Event\EventProvider::class] = function () {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Event\EvenementEventProvider();
};

$slimParams[\PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper::class] = function ($c) {
    $cache = new \Doctrine\Common\Cache\ApcuCache();
    $cache->setNamespace('PhotoContainer_userland_');

    return new \PhotoContainer\PhotoContainer\Infrastructure\Cache\DoctrineCacheHelper($cache);
};

$slimParams[PhotoContainer\PhotoContainer\Infrastructure\Persistence\RestDatabaseProvider::class] = function ($c) {
    $database = new \PhotoContainer\PhotoContainer\Infrastructure\Persistence\RestDatabaseProvider([
        'host' => 'https://viacep.com.br/ws/',
    ]);

    $database->boot();
    return $database;
};

$slimParams['PhotoContainer\PhotoContainer\Contexts\*\Domain\*Repository'] = DI\object(
    'PhotoContainer\PhotoContainer\Contexts\*\Persistence\Eloquent*Repository'
);

$slimParams[PhotoContainer\PhotoContainer\Contexts\Photo\Domain\FSPhotoRepository::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Contexts\Photo\Persistence\FilesystemPhotoRepository();
};

$slimParams[League\Event\Emitter::class] = DI\object(\League\Event\Emitter::class);

return $slimParams;