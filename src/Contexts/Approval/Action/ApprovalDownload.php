<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Command\ApprovalDownloadCommand;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\ApprovalRequestResponse;

class ApprovalDownload
{
    /**
     * @var ApprovalRepository
     */
    private $repository;

    /**
     * ApprovalDownload constructor.
     * @param ApprovalRepository $repository
     */
    public function __construct(ApprovalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ApprovalDownloadCommand $command
     * @return ApprovalRequestResponse
     */
    public function handle(ApprovalDownloadCommand $command): ApprovalRequestResponse
    {
        $request = $this->repository->findDownloadRequest($command->getEventId(), $command->getPublisherId());
        if ($request == null) {
            throw new \RuntimeException('Pedido não localizado.');
        }

        if ($request->isAuthorized()) {
            throw new \RuntimeException('Pedido já autorizado.');
        }

        $request->changeAuthorized(true);
        $request->changeActive(false);

        $request = $this->repository->approval($request);

        return new ApprovalRequestResponse($request);
    }
}
