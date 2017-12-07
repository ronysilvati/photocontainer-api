<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use League\Tactician\CommandBus;
use PhotoContainer\PhotoContainer\Contexts\Cep\Command\FindCepCommand;
use PhotoContainer\PhotoContainer\Contexts\Cep\Command\FindCitiesCommand;
use PhotoContainer\PhotoContainer\Contexts\Cep\Command\FindStatesCommand;
use PhotoContainer\PhotoContainer\Contexts\Cep\Command\GetCountriesCommand;
use PhotoContainer\PhotoContainer\Infrastructure\Web\CachedControllerResponseTrait;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Controller;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CepController extends Controller
{
    use CachedControllerResponseTrait;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * CepController constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->commandBus = $this->container->get('CachedCommandBus');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function findCep(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus->handle(
            new FindCepCommand($route->getArgument('cep'))
        );

        return $this->cachedHttpResponse($response, $domainResponse);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function findCities(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus->handle(
            new FindCitiesCommand((int) $route->getArgument('state_id'))
        );

        return $this->cachedHttpResponse($response, $domainResponse);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function findStates(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getAttribute('route');
        $domainResponse = $this->commandBus->handle(
            new FindStatesCommand((int) $route->getArgument('country_id'))
        );

        return $this->cachedHttpResponse($response, $domainResponse);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getCountries(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $domainResponse = $this->commandBus->handle(new GetCountriesCommand());
        return $this->cachedHttpResponse($response, $domainResponse);
    }
}