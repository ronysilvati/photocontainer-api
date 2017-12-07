<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Event\Command\BroadcastEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\CreateEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\CreateFavoriteCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\CreatePublisherPublicationCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\DeleteEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\DeleteFavoriteCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\FindEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\UpdateEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\UpdateSuppliersCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\UpdateTagsCommand;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EventController extends Controller
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function findEvent(ServerRequestInterface $request, ResponseInterface $response)
    {
        $args = $request->getQueryParams();
        $id = $args['id'] ?? null;

        $actionResponse = $this->commandBus()->handle(new FindEventCommand($id));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createEvent(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();

        $actionResponse = $this->commandBus()->handle(new CreateEventCommand($data));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function deleteEvent(ServerRequestInterface $request, ResponseInterface $response, int $id)
    {
        $actionResponse = $this->commandBus()->handle(new DeleteEventCommand($id));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function editEvent(ServerRequestInterface $request, ResponseInterface $response, int $id)
    {
        $data = $request->getParsedBody();

        $actionResponse = $this->commandBus()->handle(new UpdateEventCommand($id, $data));
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createFavorite(ServerRequestInterface $request, ResponseInterface $response)
    {
        $route = $request->getAttribute('route');

        $actionResponse = $this->commandBus()->handle(
            new CreateFavoriteCommand($route->getArgument('publisher_id'), $route->getArgument('event_id'))
        );
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function deleteFavorite(ServerRequestInterface $request, ResponseInterface $response) {
        $route = $request->getAttribute('route');

        $actionResponse = $this->commandBus()->handle(
            new DeleteFavoriteCommand($route->getArgument('publisher_id'), $route->getArgument('event_id'))
        );
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function updateTags(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        if (empty($data['tags'])) {
            return $response->withJson(['message' => 'Tags não enviadas.'], 204);
        }

        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus()->handle(new UpdateTagsCommand($route->getArgument('id'), $data));
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
    public function updateSuppliers(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();

        if (empty($data)) {
            return $response->withJson(['message' => 'Fornecedores não enviados.'], 204);
        }

        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus()->handle(new UpdateSuppliersCommand($route->getArgument('id'), $data));
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
    public function broadcastNewEvent(ServerRequestInterface $request, ResponseInterface $response)
    {
        $route = $request->getAttribute('route');

        $domainResponse = $this->commandBus()->handle(new BroadcastEventCommand($route->getArgument('event_id')));
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
    public function publisherPublish(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();

        $command = new CreatePublisherPublicationCommand(
            (int) $data['event_id'],
            (int) $data['publisher_id'],
            $data['text'],
            (bool) $data['ask_for_change'],
            (int) $data['approved']
        );

        $domainResponse = $this->commandBus()->handle($command);
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}