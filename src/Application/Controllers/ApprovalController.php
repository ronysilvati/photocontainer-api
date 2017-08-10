<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Approval\Action\ApprovalDownload;
use PhotoContainer\PhotoContainer\Contexts\Approval\Action\DisapprovalDownload;
use PhotoContainer\PhotoContainer\Contexts\Approval\Action\RequestDownload;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApprovalController
{
    /**
     * @var EventProvider
     */
    private $provider;

    /**
     * ApprovalController constructor.
     * @param EventProvider $provider
     */
    public function __construct(EventProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $event_id
     * @param int $publisher_id
     * @param RequestDownload $action
     * @return mixed
     */
    public function requestDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $event_id,
        int $publisher_id,
        RequestDownload $action)
    {
        $actionResponse = $action->handle($event_id, $publisher_id);

        $this->provider->addContextEvents($action->getEvents());

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $event_id
     * @param int $publisher_id
     * @param ApprovalDownload $action
     * @return mixed
     */
    public function approvalDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $event_id,
        int $publisher_id,
        ApprovalDownload $action
    ) {
        $actionResponse = $action->handle($event_id, $publisher_id);

        $this->provider->addContextEvents($action->getEvents());

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $event_id
     * @param int $publisher_id
     * @param DisapprovalDownload $action
     * @return mixed
     */
    public function disapprovalDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $event_id,
        int $publisher_id,
        DisapprovalDownload $action
    ) {
        $actionResponse = $action->handle($event_id, $publisher_id);

        $this->provider->addContextEvents($action->getEvents());

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }
}