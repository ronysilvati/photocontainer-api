<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search;

use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindCategories;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEvent;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEventPhotosPhotographer;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEventPhotosPublisher;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindHistoric;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindTags;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\GetNotifications;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\WaitingForApproval;


use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Tag;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\DbalEventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\DbalNotificationRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentCategoryRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentEventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentPhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentTagRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SearchContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->get('/search/events', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $args = $request->getQueryParams();

            $key = 'search_'.md5(serialize($args));

            $action = new FindEvent(new DbalEventRepository($container['DbalDatabaseProvider']));

            $actionResponse = $container['CacheHelper']->remember($key, function () use ($action, $args) {
                return $action->handle($args);
            });

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/search/categories', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $action = new FindCategories(new EloquentCategoryRepository($container['DatabaseProvider']));

            $actionResponse = $container['CacheHelper']->remember('categories', function () use ($action) {
                return $action->handle();
            });

            $response = $container->cache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/search/tags', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            $action = new FindTags(new EloquentTagRepository($container['DatabaseProvider']));

            $actionResponse = $container['CacheHelper']->remember('tags', function () use ($action) {
                return $action->handle();
            });

            $response = $container->cache->withExpires($response, time() + getenv('HEAD_EXPIRES'));

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/search/events/{id}/photos/user/{user_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new FindEventPhotosPublisher(new EloquentEventRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($args['id'], $args['user_id']);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/search/events/{id}/photos', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new FindEventPhotosPhotographer(new EloquentEventRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($args['id']);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/search/photo/user/{publisher_id}/{type:downloads|favorites}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $qsParams = $request->getQueryParams();
            $keyword = isset($qsParams['keyword']) ? $qsParams['keyword'] : null;

            $allTags = null;
            if (!empty($qsParams['tags'])) {
                $allTags = [];
                foreach ($qsParams['tags'] as $tag) {
                    if ($tag != "") {
                        $allTags[] = new Tag((int) $tag, null);
                    }
                }
            }

            $action = new FindHistoric(new EloquentPhotoRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($args['publisher_id'], $keyword, $allTags, $args['type']);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/search/waiting_approval/user/{photographer_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new WaitingForApproval(new EloquentEventRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($args['photographer_id']);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->get('/search/notifications/user/{user_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new GetNotifications(new DbalNotificationRepository($container['DbalDatabaseProvider']));
            $actionResponse = $action->handle($args['user_id']);

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        return $slimApp;
    }
}
