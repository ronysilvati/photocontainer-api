<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event;

use PhotoContainer\PhotoContainer\Contexts\Event\Action\CreateEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\CreateFavorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\DeleteEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\DeleteFavorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\FindEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\RequestDownload;
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
use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentEventRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EventContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->get('/events', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $args = $request->getQueryParams();
            $id = isset($args['id']) ? $args['id'] : null;

            $action = new FindEvent(new EloquentEventRepository());
            $actionResponse = $action->handle($id);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->post('/events', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
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

                $action = new CreateEvent(new EloquentEventRepository());
                $actionResponse = $action->handle($event);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->delete('/events/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $data = $request->getParsedBody();
                $id = isset($args['id']) ? $args['id'] : null;

                $action = new DeleteEvent(new EloquentEventRepository());
                $actionResponse = $action->handle($id);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->put('/events/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $data = $request->getParsedBody();
                $id = isset($args['id']) ? $args['id'] : null;

                $action = new UpdateEvent(new EloquentEventRepository());
                $actionResponse = $action->handle($id, $data);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->post('/events/{event_id}/favorite/publisher/{publisher_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $publisher = new Publisher($args['publisher_id'], null, null);
            $favorite = new Favorite(null, $publisher, $args['event_id']);

            $action = new CreateFavorite(new EloquentEventRepository());
            $actionResponse = $action->handle($favorite);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->delete('/events/{event_id}/favorite/publisher/{publisher_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $publisher = new Publisher($args['publisher_id'], null, null);
            $favorite = new Favorite(null, $publisher, $args['event_id']);

            $action = new DeleteFavorite(new EloquentEventRepository());
            $actionResponse = $action->handle($favorite);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->post('/events/{id}/tags', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $data = $request->getParsedBody();

                if (empty($data['tags'])) {
                    return $response->withJson(['message' => 'Tags não enviadas.'], 204);
                }

                $action = new UpdateTags(new EloquentEventRepository());

                $allTags = [];
                foreach ($data['tags'] as $tag) {
                    $allTags[] = new EventTag($args["id"], $tag);
                }

                $actionResponse = $action->handle($allTags, $args["id"]);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->post('/events/{id}/suppliers', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $data = $request->getParsedBody();

                if (empty($data)) {
                    return $response->withJson(['message' => 'Fornecedores não enviados.'], 204);
                }

                $action = new UpdateSuppliers(new EloquentEventRepository());
                $actionResponse = $action->handle($data, $args["id"]);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        return $slimApp;
    }
}
