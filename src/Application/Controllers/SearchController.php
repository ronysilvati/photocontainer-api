<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Search\Command\FindEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Command\FindEventPhotosPhotographerCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Command\FindEventPhotosPublisherCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Command\FindHistoricCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Command\FindTagsCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Command\GetNotificationsCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Command\SearchResourceCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Command\WaitingForApprovalCommand;
use PhotoContainer\PhotoContainer\Infrastructure\Web\CachedControllerResponseTrait;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Controller;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class SearchController extends Controller
{
    use CachedControllerResponseTrait;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function searchEvent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();

        $command = new FindEventCommand(
            (int) $params['photographer'],
            (int) $params['publisher'],
            $params['keyword'],
            isset($params['categories']) && is_array($params['categories']) ? $params['categories'] : null,
            isset($params['tags']) && is_array($params['tags']) ? $params['tags'] : null
        );

        $domainResponse = $this->commandBus()->handle($command);
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $resource
     * @return ResponseInterface
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function searchResources(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $resource
    ): ResponseInterface {
        $params = $request->getQueryParams();

        $domainResponse = $this->commandBus()->handle(new SearchResourceCommand($resource, $params));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function searchTags(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $domainResponse = $this->cachedCommandBus()->handle(new FindTagsCommand());
        return $this->cachedHttpResponse($response, $domainResponse);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function searchEventPhotosPublisher(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        /** @var  $route */
        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus()->handle(
            new FindEventPhotosPublisherCommand($route->getArgument('event_id'), $route->getArgument('user_id'))
        );
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function searchEventPhotosPhotographer(ServerRequestInterface $request, ResponseInterface $response)
    {
        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus()->handle(
            new FindEventPhotosPhotographerCommand($route->getArgument('photographer_id'))
        );
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function publisherHistoric(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getAttribute('route');
        $qsParams = $request->getQueryParams();

        $domainResponse = $this->commandBus()->handle(
            new FindHistoricCommand(
                $route->getArgument('publisher_id'),
                $qsParams['keyword'] ?? null,
                $qsParams['tags'] ?? null,
                $route->getArgument('type')
            )
        );

        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function waitingForApproval(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var  $route */
        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus()->handle(
            new WaitingForApprovalCommand($route->getArgument('photographer_id'))
        );
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function notifications(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var  $route */
        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus()->handle(new GetNotificationsCommand($route->getArgument('user_id')));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}