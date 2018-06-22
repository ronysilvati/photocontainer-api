<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Event\Action\BroadcastEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\CreateEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\CreateFavorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\CreatePublisherPublication;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\DeleteEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\DeleteFavorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\FindEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\UpdateEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\UpdateSuppliers;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\UpdateTags;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventTag;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Suppliers;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EventController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindEvent $action
     * @return mixed
     */
    public function findEvent(ServerRequestInterface $request, ResponseInterface $response, FindEvent $action)
    {
        $args = $request->getQueryParams();
        $id = $args['id'] ?? null;

        $actionResponse = $action->handle($id);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param CreateEvent $action
     * @return mixed
     */
    public function createEvent(ServerRequestInterface $request, ResponseInterface $response, CreateEvent $action)
    {
        $data = $request->getParsedBody();

        $user = new Photographer($data['user_id']);

        $allCategories = [];
        foreach ($data['categories'] as $category) {
            $allCategories[] = new EventCategory(null, $category);
        }

        $allTags = [];
        foreach ($data['tags'] as $tag) {
            $allTags[] = new EventTag(null, $tag);
        }

        $event = new Event(null, $user, $data['bride'], $data['groom'], $data['eventDate'],
            $data['title'], $data['description'], $data['country'], $data['state'], $data['city'],
            (bool) $data['terms'], (bool) $data['approval_general'],
            (bool) $data['approval_photographer'], (bool) $data['approval_bride'], $allCategories,
            $allTags, new Suppliers(null, null, null));

        $actionResponse = $action->handle($event);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param DeleteEvent $action
     * @param int $id
     * @return mixed
     */
    public function deleteEvent(ServerRequestInterface $request, ResponseInterface $response, DeleteEvent $action, int $id)
    {
        $actionResponse = $action->handle($id);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param UpdateEvent $action
     * @param int $id
     * @return mixed
     */
    public function editEvent(ServerRequestInterface $request, ResponseInterface $response, UpdateEvent $action, int $id)
    {
        $data = $request->getParsedBody();
        $actionResponse = $action->handle($id, $data);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param CreateFavorite $action
     * @param int $event_id
     * @param int $publisher_id
     * @return mixed
     */
    public function createFavorite(
        ServerRequestInterface $request,
        ResponseInterface $response,
        CreateFavorite $action,
        int $event_id,
        int $publisher_id
    ) {
        $publisher = new Publisher($publisher_id, null, null);
        $favorite = new Favorite(null, $publisher, $event_id);

        $actionResponse = $action->handle($favorite);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());

    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param DeleteFavorite $action
     * @param int $event_id
     * @param int $publisher_id
     * @return mixed
     */
    public function deleteFavorite(
        ServerRequestInterface $request,
        ResponseInterface $response,
        DeleteFavorite $action,
        int $event_id,
        int $publisher_id
    ) {
        $publisher = new Publisher($publisher_id, null, null);
        $favorite = new Favorite(null, $publisher, $event_id);

        $actionResponse = $action->handle($favorite);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param UpdateTags $action
     * @param int $id
     * @return mixed
     */
    public function updateTags(
        ServerRequestInterface $request,
        ResponseInterface $response,
        UpdateTags $action,
        int $id
    ) {
        $data = $request->getParsedBody();

        if (empty($data['tags'])) {
            return $response->withJson(['message' => 'Tags não enviadas.'], 204);
        }

        $allTags = [];
        foreach ($data['tags'] as $tag) {
            $allTags[] = new EventTag($id, $tag);
        }

        $actionResponse = $action->handle($allTags, $id);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param UpdateSuppliers $action
     * @param int $id
     * @return mixed
     */
    public function updateSuppliers(
        ServerRequestInterface $request,
        ResponseInterface $response,
        UpdateSuppliers $action,
        int $id
    ) {
        $data = $request->getParsedBody();

        if (empty($data)) {
            return $response->withJson(['message' => 'Fornecedores não enviados.'], 204);
        }

        $actionResponse = $action->handle($data, $id);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param BroadcastEvent $action
     * @param int $event_id
     * @return mixed
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function broadcastNewEvent(
        ServerRequestInterface $request,
        ResponseInterface $response,
        BroadcastEvent $action,
        int $event_id
    ) {
        $actionResponse = $action->handle($event_id);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param CreatePublisherPublication $action
     * @return mixed
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     */
    public function publisherPublish(
        ServerRequestInterface $request,
        ResponseInterface $response,
        CreatePublisherPublication $action
    ) {
        $actionResponse = $action->handle($request);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }
}