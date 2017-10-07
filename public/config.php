<?php

use Interop\Queue\PsrContext;
use League\Event\Emitter;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\EnqueueHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftQueueSpool;
use Enqueue\Dbal\DbalConnectionFactory;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\DbalDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\EventPhotoHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\JwtGenerator;

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
    ]
];

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

$defaultDI[PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper::class] = function ($c) {
    if (getenv('ENVIRONMENT') === 'prod') {
        $transport = new Swift_SendmailTransport('/usr/lib/sendmail -bs');
    } else {
        $transport = new Swift_SmtpTransport('192.168.99.100','1025');
    }

    return new \PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftMailerHelper($transport);
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
    $dsn = 'mysql://'.getenv('MYSQL_USER').':'.getenv('MYSQL_PASSWORD').
        '@'.getenv('MYSQL_HOST').':3306/'.getenv('MYSQL_DATABASE');

    $factory = new DbalConnectionFactory($dsn);

//    $factory = new FsConnectionFactory('file://dados/www/api.teste.fotocontainer.com.br/cache');

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

return $defaultDI;