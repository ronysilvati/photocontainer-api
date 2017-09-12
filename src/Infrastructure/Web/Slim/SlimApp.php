<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web\Slim;

use League\Event\Emitter;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventRecorder;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventWrapper;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Middleware\JwtAuthentication;

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
     */
    public function bootstrap(array $conf)
    {
        $app = $this->app;
        $container = $app->getContainer();        

        require ROOT_DIR.'/src/Application/Resources/routes.php';
        require ROOT_DIR.'/src/Application/Resources/listeners.php';

        $this->app->add(new JwtAuthentication([
            "secret" => $conf["secret"],
            "path" => $conf["api_path"],
            "passthrough" => $conf["auth_whitelist"],
        ]));

        $this->app->add(function (ServerRequestInterface $req, ResponseInterface $res, $next) {
            /** @var ResponseInterface $response */
            $response = $next($req, $res);

            /** @var \League\Event\Emitter $eventEmitter */
            $eventEmitter = $this->get(Emitter::class);

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
    }

    public function run()
    {
        $this->app->run();
    }
}
