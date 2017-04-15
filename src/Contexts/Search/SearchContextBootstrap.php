<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search;

use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindCategories;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEvent;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEventPhotos;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindHistoric;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindTags;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\GetNotifications;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\WaitingForApproval;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Category;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventSearch;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Tag;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentCategoryRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentEventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentNotificationRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentPhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentTagRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearchApproval;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SearchContextBootstrap implements ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->get('/search/events', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $args = $request->getQueryParams();

                $keyword = isset($args['keyword']) ? $args['keyword'] : null;
                $photographer = new Photographer((int) $args['photographer'] ?? $args['photographer']);
                $page = isset($args['page']) ? $args['page'] : 1 ;

                $allCategories = null;
                if (!empty($args['categories'])) {
                    $allCategories = [];
                    foreach ($args['categories'] as $category) {
                        $allCategories[] = new Category((int) $category);
                    }
                }

                $allTags = null;
                if (!empty($args['tags'])) {
                    $allTags = [];
                    foreach ($args['tags'] as $tag) {
                        $allTags[] = new Tag((int) $tag, null);
                    }
                }

                $search = new EventSearch(null, $photographer, $keyword, $allCategories, $allTags, $page);

                if (!empty($args['publisher'])) {
                    $search->changePublisher(new Publisher((int) $args['publisher'] ?? $args['publisher']));
                }

                $action = new FindEvent(new EloquentEventRepository());
                $actionResponse = $action->handle($search);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/search/categories', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $action = new FindCategories(new EloquentCategoryRepository());
                $actionResponse = $action->handle();

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/search/tags', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
            try {
                $action = new FindTags(new EloquentTagRepository());
                $actionResponse = $action->handle();

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/search/events/{id}/photos/user/{user_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $action = new FindEventPhotos(new EloquentEventRepository());
                $actionResponse = $action->handle($args['id'], $args['user_id']);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/search/photo/user/{publisher_id}/{type:downloads|favorites}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
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

                $action = new FindHistoric(new EloquentPhotoRepository());
                $actionResponse = $action->handle($args['publisher_id'], $keyword, $allTags, $args['type']);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/search/waiting_approval/user/{photographer_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $action = new WaitingForApproval(new EloquentEventRepository());
                $actionResponse = $action->handle($args['photographer_id']);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        $slimApp->app->get('/search/notifications/user/{user_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            try {
                $action = new GetNotifications(new EloquentNotificationRepository());
                $actionResponse = $action->handle($args['user_id']);

                return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
            } catch (\Exception $e) {
                return $response->withJson(['message' => $e->getMessage()], 500);
            }
        });

        return $slimApp;
    }
}