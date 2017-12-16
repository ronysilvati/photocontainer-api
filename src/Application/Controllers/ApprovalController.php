<?php

namespace PhotoContainer\PhotoContainer\Application\Controllers;

use PhotoContainer\PhotoContainer\Contexts\Approval\Command\ApprovalDownloadCommand;
use PhotoContainer\PhotoContainer\Contexts\Approval\Command\DisapprovalDownloadCommand;
use PhotoContainer\PhotoContainer\Contexts\Approval\Command\RequestDownloadCommand;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApprovalController extends Controller
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $event_id
     * @param int $publisher_id
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function requestDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $event_id,
        int $publisher_id
    ) {
        $domainResponse = $this->commandBus()->handle(new RequestDownloadCommand($event_id, $publisher_id));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $eventId
     * @param int $publisherId
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function approvalDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $eventId,
        int $publisherId
    ) {
        $domainResponse = $this->commandBus()->handle(new ApprovalDownloadCommand($eventId, $publisherId));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param int $eventId
     * @param int $publisherId
     * @return mixed
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function disapprovalDownload(
        ServerRequestInterface $request,
        ResponseInterface $response,
        int $eventId,
        int $publisherId
    ) {
        $domainResponse = $this->commandBus()->handle(new DisapprovalDownloadCommand($eventId, $publisherId));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}