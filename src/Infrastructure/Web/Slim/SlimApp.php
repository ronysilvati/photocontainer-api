<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web\Slim;

use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventRecorder;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventWrapper;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Http\Response;
use Slim\Middleware\JwtAuthentication;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;

class SlimApp implements WebApp
{
    /**
     * @var App
     */
    public $app;

    /**
     * SlimApp constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param array $conf
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function bootstrap(array $conf): void
    {
        $app = $this->app;
        $container = $app->getContainer();
        $eventDispatcher = $container->get('EventDispatcher');

        $container->get(EloquentDatabaseProvider::class);

        require_once ROOT_DIR.'/src/Application/Resources/routes.php';
        require_once ROOT_DIR.'/src/Application/Resources/listeners.php';

        if (DEBUG_MODE) {
            $app->add(new WhoopsMiddleware($app));
        }

        $this->app->add(new JwtAuthentication([
            'secret' => $conf['secret'],
            'path' => $conf['api_path'],
            'passthrough' => $conf['auth_whitelist'],
        ]));

        $this->app->add(function (ServerRequestInterface $req, ResponseInterface $res, $next) {
            /** @var ResponseInterface $response */
            $response = $next($req, $res);

            /** @var \League\Event\Emitter $eventEmitter */
            $eventEmitter = $this->get('EventDispatcher');

            $events = EventRecorder::getInstance()->pullEvents();

            if (count($events) > 0) {
                foreach ($events as $event) {
                    $eventEmitter->emit(new EventWrapper($event));
                }
            }

            return $response;
        });

        $this->app->options('/{routes:.+}', function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        });

        $this->app->add(function (ServerRequestInterface $request, ResponseInterface $response, $next) {
            /** @var ResponseInterface $response */
            $response = $next($request, $response);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, X-Requested-With, Content-Type, Accept, Origin, Authorization, Cache-Control')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
                ->withHeader('Access-Control-Max-Age', '604800');
        });

        $this->app->get('/purge', function (ServerRequestInterface $request, ResponseInterface $response) {
            /** @var CacheHelper $cache */
            $cache = $this->get(CacheHelper::class);
            $cache->purge();
            return new Response(204);
        });

        $container->get(EloquentDatabaseProvider::class);
    }

    public function run(): void
    {
        $this->app->run();
    }
}
