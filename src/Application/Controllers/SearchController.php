<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindCategories;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEvent;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEventPhotosPhotographer;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindEventPhotosPublisher;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindHistoric;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\FindTags;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\GetNotifications;
use PhotoContainer\PhotoContainer\Contexts\Search\Action\WaitingForApproval;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Tag;
use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\HttpCache\CacheProvider;

class SearchController
{
    /**
     * @var CacheHelper
     */
    private $cacheHelper;

    /**
     * @var CacheProvider
     */
    private $cacheProvider;

    /**
     * SearchController constructor.
     * @param CacheHelper $cacheHelper
     * @param CacheProvider $cacheProvider
     */
    public function __construct(CacheHelper $cacheHelper, CacheProvider $cacheProvider)
    {
        $this->cacheHelper = $cacheHelper;
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindEvent $action
     * @return mixed
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function searchEvent(ServerRequestInterface $request, ResponseInterface $response, FindEvent $action)
    {
        $actionResponse = $action->handle($request->getQueryParams());
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindCategories $action
     * @return mixed
     */
    public function searchCategories(
        ServerRequestInterface $request,
        ResponseInterface $response,
        FindCategories $action
    ) {
        $actionResponse = $this->cacheHelper->remember('categories', function () use ($action) {
            return $action->handle();
        });

        $response = $this->cacheProvider->withExpires($response, time() + getenv('HEAD_EXPIRES'));

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindTags $action
     * @return mixed
     */
    public function searchTags(ServerRequestInterface $request, ResponseInterface $response, FindTags $action)
    {
        $actionResponse = $this->cacheHelper->remember('tags', function () use ($action) {
            return $action->handle();
        });

        $response = $this->cacheProvider->withExpires($response, time() + getenv('HEAD_EXPIRES'));

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindEventPhotosPublisher $action
     * @param int $event_id
     * @param int $user_id
     * @return mixed
     */
    public function searchEventPhotosPublisher(
        ServerRequestInterface $request,
        ResponseInterface $response,
        FindEventPhotosPublisher $action,
        int $event_id,
        int $user_id
    ) {
        $actionResponse = $action->handle($event_id, $user_id);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindEventPhotosPhotographer $action
     * @param int $photographer_id
     * @return mixed
     */
    public function searchEventPhotosPhotographer(
        ServerRequestInterface $request,
        ResponseInterface $response,
        FindEventPhotosPhotographer $action,
        int $photographer_id
    ) {
        $actionResponse = $action->handle($photographer_id);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param FindHistoric $action
     * @param int $publisher_id
     * @param string $type
     * @return mixed
     */
    public function publisherHistoric(
        ServerRequestInterface $request,
        ResponseInterface $response,
        FindHistoric $action,
        int $publisher_id,
        string $type
    ) {
        $qsParams = $request->getQueryParams();
        $keyword = $qsParams['keyword'] ?? null;

        $allTags = null;
        if (!empty($qsParams['tags'])) {
            $allTags = [];
            foreach ($qsParams['tags'] as $tag) {
                if ($tag != '') {
                    $allTags[] = new Tag((int) $tag, null);
                }
            }
        }

        $actionResponse = $action->handle($publisher_id, $keyword, $allTags, $type);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param WaitingForApproval $action
     * @param int $photographer_id
     * @return mixed
     */
    public function waitingForApproval(
        ServerRequestInterface $request,
        ResponseInterface $response,
        WaitingForApproval $action,
        int $photographer_id
    ) {
        $actionResponse = $action->handle($photographer_id);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param GetNotifications $action
     * @param int $user_id
     * @return mixed
     */
    public function notifications(
        ServerRequestInterface $request,
        ResponseInterface $response,
        GetNotifications $action,
        int $user_id
    ) {
        $actionResponse = $action->handle($user_id);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }
}