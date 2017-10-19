<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Cep\Action\FindCep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Action\FindCities;
use PhotoContainer\PhotoContainer\Contexts\Cep\Action\FindStates;
use PhotoContainer\PhotoContainer\Contexts\Cep\Action\GetCountries;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\HttpCache\CacheProvider;

class CepController
{
    /**
     * @var CacheProvider
     */
    private $httpCache;

    public function __construct(CacheProvider $httpCache)
    {
        $this->httpCache = $httpCache;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param GetCountries $action
     * @return mixed
     */
    public function getCountries(ServerRequestInterface $request, ResponseInterface $response, GetCountries $action)
    {
        $actionResponse = $action->handle();

        $response = $this->httpCache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindCep $action
     * @param string $cep
     * @return mixed
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function getCep(ServerRequestInterface $request, ResponseInterface $response, FindCep $action, string $cep)
    {
        $actionResponse = $action->handle($cep);

        $response = $this->httpCache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindStates $action
     * @param int $country_id
     * @return mixed
     */
    public function getStates(
        ServerRequestInterface $request,
        ResponseInterface $response,
        FindStates $action, int $country_id
    ) {
        $actionResponse = $action->handle($country_id);

        $response = $this->httpCache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindCities $action
     * @param int $state_id
     * @return mixed
     */
    public function getCities(
        ServerRequestInterface $request,
        ResponseInterface $response,
        FindCities $action,
        int $state_id
    ) {
        $actionResponse = $action->handle($state_id);

        $response = $this->httpCache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }
}