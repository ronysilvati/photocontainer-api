<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event;

use PhotoContainer\PhotoContainer\Contexts\Event\Action\CreateEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\FindCategories;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\FindEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\FindTags;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Category;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventTag;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Search;
use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentCategoryRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentEventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentTagRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EventContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

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
                    $data['title'], $data['description'], (bool) $data['terms'], (bool) $data['approval_general'],
                    (bool) $data['approval_photographer'], (bool) $data['approval_bride'], $allCategories,
                    $allTags);

                $action = new CreateEvent(new EloquentEventRepository());
                $actionResponse = $action->handle($event);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/events', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $args = $request->getQueryParams();

                $keyword = isset($args['keyword']) ? $args['keyword'] : null;
                $photographer = isset($args['photographer']) ? $args['photographer'] : null;

                $allCategories = [];
                if (isset($args['categories'])) {
                    foreach ($args['categories'] as $category) {
                        $allCategories[] = new Category($category);
                    }
                }

                $search = new Search(null, $photographer, $keyword, $allCategories);

                $action = new FindEvent(new EloquentEventRepository());
                $actionResponse = $action->handle($search);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/events/categories', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $action = new FindCategories(new EloquentCategoryRepository());
                $actionResponse = $action->handle();

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/events/tags', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $action = new FindTags(new EloquentTagRepository());
                $actionResponse = $action->handle();

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        return $slimApp;
    }
}