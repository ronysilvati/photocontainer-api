<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web\Slim;

use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EvenementEventProvider;
use PhotoContainer\PhotoContainer\Infrastructure\Listeners\SendEmail;
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
        $this->app->add(new JwtAuthentication([
            "secret" => $conf["secret"],
            "path" => $conf["api_path"],
            "passthrough" => $conf["auth_whitelist"],
        ]));

        /** @var EvenementEventProvider $eventEmitter */
        $eventEmitter = $this->app->getContainer()->get('EventEmitter');
        $this->app->add(function (ServerRequestInterface $req, ResponseInterface $res, $next) use ($eventEmitter) {
            /** @var ResponseInterface $response */
            $response = $next($req, $res);
            $eventEmitter->releaseAllEvents();
            return $response;
        });

        $this->addGenericEvents();

        $this->app->options('/{routes:.+}', function (ServerRequestInterface $req, ResponseInterface $res) {
            return $res;
        });

        $this->app->add(function (ServerRequestInterface $req, ResponseInterface $res, $next) {
            /** @var ResponseInterface $response */
            $response = $next($req, $res);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'Access-Control-Allow-Origin, X-Requested-With, Content-Type, Accept, Origin, Authorization, Cache-Control')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
                ->withHeader('Access-Control-Max-Age', '604800');
        });
    }

    public function addGenericEvents()
    {
        $listener = new SendEmail($this->app->getContainer()->get('EmailHelper'));
        $this->app->getContainer()->get('EventEmitter')->on('generic.sendmail', function (Email $mail) use ($listener) {
            $listener($mail);
        });
    }

    /**
     * @param ContextBootstrap $context
     * @return $this
     */
    public function addContext(ContextBootstrap $context)
    {
        $this->app = ($context->wireSlimRoutes($this))->app;
        return $this;
    }

    public function run()
    {
        $this->app->run();
    }
}
