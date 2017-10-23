<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web\Slim;

use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Event\Event;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventRecorder;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventWrapper;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
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
     * @var ContainerInterface
     */
    private $container;

    /**
     * SlimApp constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $app->getContainer();
    }

    /**
     * @param array $conf
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function bootstrap(array $conf): void
    {
        $cacheHelper = $this->container->get(CacheHelper::class);
        $this->loadRoutes($cacheHelper);
        $this->setListeners($cacheHelper);

        $this->container->get(EloquentDatabaseProvider::class);

        $this->middlewares($conf);
        $this->defaultRoutes();
    }

    private function defaultRoutes(): void
    {
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
    }

    /**
     * @param array $conf
     */
    private function middlewares(array $conf): void
    {
        if (DEBUG_MODE) {
            $this->app->add(new WhoopsMiddleware($this->app));
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

            /** @var LoggerInterface $logger */
            $logger = $this->get('eventLogger');

            $events = EventRecorder::getInstance()->pullEvents();

            if (count($events) > 0) {
                $data = [
                    'route' => $req->getMethod(). ' ' . $req->getUri()->getPath(),
                ];

                try {
                    /** @var Event $event */
                    foreach ($events as $event) {
                        $eventEmitter->emit(new EventWrapper($event));
                        $logger->debug('Emitido: '.$event->getName(), $data);
                    }
                } catch (\Exception $e) {
                    $data['exception'] = $e->getMessage();
                    $data['file'] = $e->getFile() . ': '. $e->getLine();

                    $logger->error('Emitido: '.$event->getName(), $data);
                }
            }

            return $response;
        });
    }

    /**
     * @param CacheHelper $cacheHelper
     */
    private function loadRoutes(CacheHelper $cacheHelper)
    {
        $routes = $cacheHelper->remember('all_routes', function () {
            $filename = ROOT_DIR.'/src/Application/Resources/routes.yml';
            return yaml_parse_file($filename);
        });

        foreach ($routes as $controller => $actions) {
            foreach ($actions as $action => $config) {
                $this->app->map([$config['verb']], $config['route'], [$controller, $action]);
            }
        }
    }

    /**
     * @param CacheHelper $cacheHelper
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function setListeners(CacheHelper $cacheHelper)
    {
        $eventDispatcher = $this->container->get('EventDispatcher');

        $listeners = $cacheHelper->remember('all_listeners', function () {
            $filename = ROOT_DIR.'/src/Application/Resources/listeners.yml';
            return yaml_parse_file($filename);
        });

        foreach ($listeners as $class => $listener) {
            $eventDispatcher->addListener($listener['event'], $this->container->get($class));
        }
    }

    /**
     * @throws \Exception
     */
    public function run(): void
    {
        try {
            $this->app->run();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
