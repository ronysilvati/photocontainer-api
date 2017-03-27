<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search;

use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindCategories;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEvent;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindTags;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Category;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventSearch;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentCategoryRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\EloquentEventRepository;
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
            try {
                $args = $request->getQueryParams();

                $keyword = isset($args['keyword']) ? $args['keyword'] : null;
                $photographer = new Photographer((int) $args['photographer'] ?? $args['photographer']);

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

                $search = new EventSearch(null, $photographer, $keyword, $allCategories, $allTags);

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

        return $slimApp;
    }
}