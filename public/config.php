<?php

use Interop\Queue\PsrContext;
use League\Event\Emitter;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\EnqueueHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftQueueSpool;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\DbalDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\EventPhotoHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\JwtGenerator;
use Enqueue\Fs\FsConnectionFactory;
use Monolog\Logger;
use PhotoContainer\PhotoContainer\Infrastructure\CommandBus\Middleware\HandlerCacheMiddleware;

if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777);
}

if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR, 0777);
}

$defaultDI = [
    'database_config' => [
        'host'      => getenv('MYSQL_HOST'),
        'database'  => getenv('MYSQL_DATABASE'),
        'user'      => getenv('MYSQL_USER'),
        'pwd'       => getenv('MYSQL_PASSWORD'),
        'port'      => getenv('MYSQL_PORT'),
        'charset'  => 'utf8',
        'driverOptions' => [
            1002 => 'SET NAMES utf8'
        ]
    ]
];

$defaultDI[Psr\Log\LoggerInterface::class] = function($c) {
    $filename = getenv('ENVIRONMENT') === 'dev' ? 'dev.log' : 'prod.log';

    $logger = new Logger('API_LOG');
    $file_handler = new \Monolog\Handler\RotatingFileHandler(LOG_DIR.'/'.$filename, 7);
    $logger->pushHandler($file_handler);
    return $logger;
};

$defaultDI['eventLogger'] = function($c) {
    $logger = new Logger('EVENT_LOG');
    $file_handler = new \Monolog\Handler\RotatingFileHandler(LOG_DIR.'/events.log', 7);

    $file_handler->pushProcessor(new \Monolog\Processor\ProcessIdProcessor());

    $logger->pushHandler($file_handler);
    return $logger;
};

$defaultDI[EloquentDatabaseProvider::class] = function ($c) {
    $database = new EloquentDatabaseProvider($c->get('database_config'));
    $database->boot();
    return $database;
};

$defaultDI[DbalDatabaseProvider::class] = function ($c) {
    $database = new DbalDatabaseProvider($c->get('database_config'));
    $database->boot();
    return $database;
};

$defaultDI[PhotoContainer\PhotoContainer\Infrastructure\Crypto\CryptoMethod::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Crypto\BcryptHashing();
};

$defaultDI[JwtGenerator::class] = function ($c) {
    return new JwtGenerator('secret');
};

$defaultDI[PhotoContainer\PhotoContainer\Infrastructure\Persistence\AtomicWorker::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentAtomicWorker();
};

$defaultDI[PhotoContainer\PhotoContainer\Infrastructure\Helper\TokenGeneratorHelper::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Helper\TokenGeneratorHelper();
};

$defaultDI[PhotoContainer\PhotoContainer\Infrastructure\Helper\ProfileImageHelper::class] = DI\object(
    PhotoContainer\PhotoContainer\Infrastructure\Helper\ProfileImageHelper::class
);

$defaultDI[\PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftPoolMailerHelper::class] = function ($c) {
    $context = $c->get(PsrContext::class);

    $transport = new Swift_SpoolTransport(
        new \PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftQueueSpool($context)
    );

    return new \PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftPoolMailerHelper($transport);
};

$defaultDI['EmailTransport'] = function ($c) {
    if (getenv('TRANSPORT') === 'smtp') {
        return new Swift_SmtpTransport(getenv('SMTP_HOST'),getenv('SMTP_PORT'));
    }

    return new Swift_SendmailTransport('/usr/lib/sendmail -bs');
};

$defaultDI[PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper::class] = function ($c) {
    return new \PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftMailerHelper($c->get('EmailTransport'));
};

$defaultDI[PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper::class] = function () {
    if (getenv('ENVIRONMENT') === 'prod') {
        $cache = new \Doctrine\Common\Cache\ApcuCache();
    } else {
        $cache = new \Doctrine\Common\Cache\ArrayCache();
    }

    $cache->setNamespace('PhotoContainer_userland_');
    return new \PhotoContainer\PhotoContainer\Infrastructure\Cache\DoctrineCacheHelper($cache);
};

$defaultDI[PhotoContainer\PhotoContainer\Infrastructure\Persistence\RestDatabaseProvider::class] = function ($c) {
    $database = new \PhotoContainer\PhotoContainer\Infrastructure\Persistence\RestDatabaseProvider([
        'host' => 'https://viacep.com.br/ws/',
    ]);

    $database->boot();
    return $database;
};

$defaultDI['EventDispatcher'] = DI\object(Emitter::class);

$defaultDI[PsrContext::class] = function ($c) {
    $factory = new FsConnectionFactory(ROOT_DIR.'/var/pool');
    return $factory->createContext();
};

$defaultDI['EmailPoolProcessor'] = function ($c) {
    return new SwiftQueueSpool($c->get(PsrContext::class));
};

$defaultDI[EnqueueHelper::class] = function ($c) {
    return new EnqueueHelper($c->get(PsrContext::class));
};

$defaultDI[EventPhotoHelper::class] = function ($c) {
    return new EventPhotoHelper($c->get(EnqueueHelper::class));
};

$defaultDI['CommandBus'] = function ($c) {
    $handlerMiddleware = new \League\Tactician\Handler\CommandHandlerMiddleware(
        new \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
        new \PhotoContainer\PhotoContainer\Infrastructure\CommandBus\ContainerBasedHandlerLocator($c),
        new \League\Tactician\Handler\MethodNameInflector\HandleInflector()
    );
    return new \League\Tactician\CommandBus([$handlerMiddleware]);
};

$defaultDI['CachedCommandBus'] = function (\Psr\Container\ContainerInterface $c) {
    $handlerMiddleware = new \League\Tactician\Handler\CommandHandlerMiddleware(
        new \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
        new \PhotoContainer\PhotoContainer\Infrastructure\CommandBus\ContainerBasedHandlerLocator($c),
        new \League\Tactician\Handler\MethodNameInflector\HandleInflector()
    );

    return new \League\Tactician\CommandBus([
        new HandlerCacheMiddleware($c),
        $handlerMiddleware,
    ]);
};

$defaultDI['orm_settings'] = function (\Psr\Container\ContainerInterface $container) {
    $config = $container->get('database_config');

    $db = $container->get(DbalDatabaseProvider::class);
    return [
            'annotation_autoloaders' => ['class_exists'],
            'connection' => [
                'dbname' =>$config['database'],
                'user' => $config['user'],
                'password' => $config['pwd'],
                'host' => $config['host'],
                'port' => $config['port'],
                'driver' => 'pdo_mysql',
            ],
            'metadata_mapping' => [
                [
                    'type' => \Jgut\Slim\Doctrine\ManagerBuilder::METADATA_MAPPING_YAML,
                    'path' => ROOT_DIR.'/src/Application/Resources/doctrine',
                    'namespace' => 'PhotoContainer\\PhotoContainer\\Contexts'
                ],
            ],
            'proxies_path' => CACHE_DIR.'/proxies',
            'proxies_auto_generation' => 1

    ];
};

$defaultDI[\Doctrine\ORM\EntityManager::class] = function (\Psr\Container\ContainerInterface $container) {
    $builder = new \Jgut\Doctrine\ManagerBuilder\RelationalBuilder($container->get('orm_settings'));

    return $builder->getManager();
};

return $defaultDI;