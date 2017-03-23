<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web\Slim;

use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Slim\App;
use Slim\Middleware\JwtAuthentication;

class SlimApp implements WebApp
{
    /**
     * @var App
     */
    public $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function bootstrap(array $conf)
    {
        $container = $this->app->getContainer();
        $container['DatabaseProvider']->boot();

        $this->app->add(new JwtAuthentication([
            "secret" => $conf["secret"],
            "path" => $conf["api_path"],
            "passthrough" => $conf["auth_whitelist"],
        ]));

        $this->app->options('/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });

        $this->app->add(function ($req, $res, $next) {
            $response = $next($req, $res);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH');
        });
    }

    public function run()
    {
        $this->app->run();
    }

}