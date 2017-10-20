<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

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
     * @param int $event_id
     * @param int $publisher_id
     * @return DisapprovalRequestResponse
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function handle(int $event_id, int $publisher_id): \PhotoContainer\PhotoContainer\Contexts\Approval\Response\DisapprovalRequestResponse
    {
        $request = $this->repository->findDownloadRequest($event_id, $publisher_id);
        if ($request == null) {
            throw new \RuntimeException('Pedido não localizado.');
        }

        if ($request->isActive() == false) {
            throw new \RuntimeException('Pedido já negado.');
        }

        $request = $this->repository->disapproval($request);

        return new DisapprovalRequestResponse($request);
    }
}
