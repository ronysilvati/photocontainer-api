<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval;

use PhotoContainer\PhotoContainer\Contexts\Approval\Action\ApprovalDownload;
use PhotoContainer\PhotoContainer\Contexts\Approval\Action\DisapprovalDownload;
use PhotoContainer\PhotoContainer\Contexts\Approval\Action\RequestDownload;
use PhotoContainer\PhotoContainer\Contexts\Approval\Persistence\EloquentEventRepository;
use PhotoContainer\PhotoContainer\Infrastructure\ContextBootstrap;
use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class ApprovalContextBootstrap implements ContextBootstrap
{
    CONST ResourceRoot = 'events';

    public function wireSlimRoutes(WebApp $slimApp): WebApp
    {
        $container = $slimApp->app->getContainer();

        $slimApp->app->post('/events/{event_id}/request/user/{publisher_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new RequestDownload(new EloquentEventRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($args['event_id'], $args['publisher_id']);

            $container['EventEmitter']->addContextEvents($action->getEvents());

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->put('/events/{event_id}/approval/user/{publisher_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new ApprovalDownload(new EloquentEventRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($args['event_id'], $args['publisher_id']);

            $container['EventEmitter']->addContextEvents($action->getEvents());

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        $slimApp->app->put('/events/{event_id}/disapproval/user/{publisher_id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
            $action = new DisapprovalDownload(new EloquentEventRepository($container['DatabaseProvider']));
            $actionResponse = $action->handle($args['event_id'], $args['publisher_id']);

            $container['EventEmitter']->addContextEvents($action->getEvents());

            return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
        });

        return $slimApp;
    }
}
