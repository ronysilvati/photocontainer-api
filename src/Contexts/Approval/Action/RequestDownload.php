<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\DownloadRequestResponse;

class RequestDownload
{
    /**
     * @var ApprovalRepository
     */
    private $repository;

    /**
     * RequestDownload constructor.
     * @param ApprovalRepository $repository
     */
    public function __construct(ApprovalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     * @return DownloadRequestResponse
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function handle(int $event_id, int $publisher_id): \PhotoContainer\PhotoContainer\Contexts\Approval\Response\DownloadRequestResponse
    {
        $dlRequest = $this->repository->findDownloadRequest($event_id, $publisher_id);
        if ($dlRequest) {
            $msg = !$dlRequest->isActive() && !$dlRequest->isAuthorized() ? 'Seu pedido não foi autorizado.' : 'Seu pedido para download ainda está sendo analisado.';
            throw new \RuntimeException($msg);
        }

        $dlRequest = new DownloadRequest(
            null,
            $event_id,
            $publisher_id,
            false,
            false,
            true
        );

        $event = $this->repository->createDownloadRequest($dlRequest);
        return new DownloadRequestResponse($event);
    }

}
