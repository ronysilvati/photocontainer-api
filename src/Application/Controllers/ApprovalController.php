<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Approval\Action\ApprovalDownload;
use PhotoContainer\PhotoContainer\Contexts\Approval\Action\DisapprovalDownload;
use PhotoContainer\PhotoContainer\Contexts\Approval\Action\RequestDownload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApprovalController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $event_id
     * @param int $publisher_id
     * @param RequestDownload $action
     * @return mixed
     * @throws \Exception
     */
    public function requestDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $event_id,
        int $publisher_id,
        RequestDownload $action
    ) {
        $actionResponse = $action->handle($event_id, $publisher_id);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $event_id
     * @param int $publisher_id
     * @param ApprovalDownload $action
     * @return mixed
     * @throws \Exception
     */
    public function approvalDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $event_id,
        int $publisher_id,
        ApprovalDownload $action
    ) {
        $actionResponse = $action->handle($event_id, $publisher_id);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $event_id
     * @param int $publisher_id
     * @param DisapprovalDownload $action
     * @return mixed
     * @throws \Exception
     */
    public function disapprovalDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $event_id,
        int $publisher_id,
        DisapprovalDownload $action
    ) {
        $actionResponse = $action->handle($event_id, $publisher_id);
        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    }
}