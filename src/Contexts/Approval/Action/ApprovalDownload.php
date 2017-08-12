<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

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
     * @param int $event_id
     * @param int $publisher_id
     * @return ApprovalRequestResponse
     * @throws \Exception
     */
    public function handle(int $event_id, int $publisher_id)
    {
        $request = $this->repository->findDownloadRequest($event_id, $publisher_id);
        if ($request == null) {
            throw new \Exception('Pedido não localizado.');
        }

        if ($request->isAuthorized()) {
            throw new \Exception('Pedido já autorizado.');
        }

        $request = $this->repository->approval($request);

        return new ApprovalRequestResponse($request);
    }
}
