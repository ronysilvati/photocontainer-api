<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep;

use PhotoContainer\PhotoContainer\Contexts\Cep\Action\FindCep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Action\FindCities;
use PhotoContainer\PhotoContainer\Contexts\Cep\Action\FindStates;
use PhotoContainer\PhotoContainer\Contexts\Cep\Action\GetCountries;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Persistence\EloquentCepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Persistence\RestCepRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CepContextBootstrap implements ContextBootstrap
{
    CONST ResourceRoot = 'location';

    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->get('/location/countries', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $action = new GetCountries(new EloquentCepRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle();

            $response = $container->cache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/location/zipcode/{cep}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new FindCep(new RestCepRepository($container['CepRestProvider']));
            $actionResponse = $action->handle(new Cep($args['cep'], null, null, null, null, null, null));

            $response = $container->cache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/location/country/{country_id}/states', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new FindStates(new EloquentCepRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle(new Cep(null, $args['country_id'], null, null, null, null, null));

            $response = $container->cache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/location/state/{state_id}/cities', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new FindCities(new EloquentCepRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle(new Cep(null, null, $args['state_id'], null, null, null, null));

            $response = $container->cache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        return $slimApp;
    }
}
