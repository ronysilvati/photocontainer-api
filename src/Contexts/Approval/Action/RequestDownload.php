<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Command\RequestDownloadCommand;
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
     * @param RequestDownloadCommand $command
     * @return DownloadRequestResponse
     */
    public function handle(RequestDownloadCommand $command): DownloadRequestResponse
    {
        $dlRequest = $this->repository->findDownloadRequest($command->getEventId(), $command->getPublisherId());

        if ($dlRequest) {
            $msg = !$dlRequest->isActive() && !$dlRequest->isAuthorized() ?
                'Seu pedido não foi autorizado.' :
                'Seu pedido para download ainda está sendo analisado.';
            throw new \RuntimeException($msg);
        }

        $dlRequest = new DownloadRequest(
            $command->getEventId(),
            $command->getPublisherId(),
            false,
            false,
            true
        );

        $event = $this->repository->createDownloadRequest($dlRequest);
        return new DownloadRequestResponse($event);
    }

}
