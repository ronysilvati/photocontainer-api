<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\CommandBus;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Route;

class CommandBusController
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface|\Psr\Container\NotFoundExceptionInterface|\RuntimeException
     */
    public function dispatcher(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var Route $route */
        $route = $request->getAttribute('route');
        $commandName = $this->container->get($route->getPattern());

        if (!$commandName) {
            throw new \RuntimeException("Command '$commandName' não encontrado. Implemente este command e sua action.");
        }

        $bodyData = $request->getParsedBody() ?? [];
        $queryData = $request->getAttribute('route')->getArguments() ?? [];

        $command = new $commandName($request);

        $domainResponse = $this->container->get('CommandBus')->handle($command);
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}