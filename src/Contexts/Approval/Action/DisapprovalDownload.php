<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Command\DisapprovalDownloadCommand;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\DisapprovalRequestResponse;

class DisapprovalDownload
{
    /**
     * @var ApprovalRepository
     */
    private $repository;

    /**
     * DisapprovalDownload constructor.
     * @param ApprovalRepository $repository
     */
    public function __construct(ApprovalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DisapprovalDownloadCommand $command
     * @return DisapprovalRequestResponse
     */
    public function handle(DisapprovalDownloadCommand $command): DisapprovalRequestResponse
    {
        $request = $this->repository->findDownloadRequest($command->getEventId(), $command->getPublisherId());
        if ($request == null) {
            throw new \RuntimeException('Pedido não localizado.');
        }

        if ($request->isActive() == false) {
            throw new \RuntimeException('Pedido já negado.');
        }

        $request->changeAuthorized(false);
        $request->changeActive(false);

        $request = $this->repository->disapproval($request);

        return new DisapprovalRequestResponse($request);
    }
}
