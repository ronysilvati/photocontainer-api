<?php

use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface;

$slimParams = [
    'settings.responseChunkSize' => 4096,
    'settings.outputBuffering' => 'append',
    'settings.determineRouteBeforeAppMiddleware' => false,
    'settings.displayErrorDetails' => false,
    'php-di' => [
        'proxy_path' => CACHE_DIR,
    ],
];

if (!is_file(CACHE_DIR.'/routes.cache')) {
    $slimParams = ['settings.routerCacheFile' => CACHE_DIR.'/routes.cache'];
}

if (DEBUG_MODE) {
    $slimParams = [
        'settings.displayErrorDetails' => true,
        'settings.debug' => true,
    ];
}

$slimParams['logger'] = function($c) {
    return $c->get(LoggerInterface::class);
};

$slimParams['errorHandler'] = function ($c) {
    return function (ServerRequestInterface $request, $response, Exception $e) use ($c) {
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

        $message = $e instanceof PersistenceException ? $e->getInfraLayerError() : $e->getMessage();

        $c->get('logger')->addCritical($message, $data);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, X-Requested-With, Content-Type, Accept, Origin, Authorization, Cache-Control, Expires')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
            ->withHeader('Access-Control-Max-Age', '604800')
            ->withJson(['message' => $e->getMessage()], 500);
    };
};

return $slimParams;