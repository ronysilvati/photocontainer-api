<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web\Slim;

use PhotoContainer\PhotoContainer\Application\Controllers\CommandBusController;
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
     * @var mixed|CacheHelper
     */
    private $cacheHelper;

    /**
     * SlimApp constructor.
     * @param App $app
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $app->getContainer();
        $this->cacheHelper = $this->container->get(CacheHelper::class);
    }

    /**
     * @param array $conf
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function bootstrap(array $conf): void
    {
        $this->loadRoutes();
        $this->setListeners();

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
            $this->set('request', $req);

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

    private function loadRoutes()
    {
        $routes = $this->cachedYamlLoader('routes.yml', 'all_routes');

        foreach ($routes as $controller => $actions) {
            foreach ($actions as $action => $config) {
                if (!isset($config['verb'])) {
                    foreach ($config as $command => $routing) {
                        $this->container->set($routing['route'], $command);
                        $this->app->map([$routing['verb']], $routing['route'], [$controller, $action]);
                    }
                } else {
                    $this->app->map([$config['verb']], $config['route'], [$controller, $action]);
                }
            }
        }
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function setListeners()
    {
        $listeners = $this->cachedYamlLoader('listeners.yml', 'all_listeners');

        $eventDispatcher = $this->container->get('EventDispatcher');
        foreach ($listeners as $class => $listener) {
            $eventDispatcher->addListener($listener['event'], $this->container->get($class));
        }
    }

    /**
     * @param string $filename
     * @param string $cacheKey
     * @return array
     */
    private function cachedYamlLoader(string $filename, string $cacheKey): array
    {
        return $this->cacheHelper->remember($cacheKey, function () use ($filename) {
            $filename = ROOT_DIR.'/src/Application/Resources/'.$filename;
            return yaml_parse_file($filename);
        });
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
