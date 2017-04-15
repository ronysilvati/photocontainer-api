<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\ApprovalRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class ApprovalDownload
{
    protected $repository;

    public function __construct(ApprovalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $event_id, int $user_id)
    {
        try {
            $request = $this->repository->findDownloadRequest($event_id, $user_id);
            if ($request == null) {
                throw new \Exception('Pedido não localizado.');
            }

            $request = $this->repository->approval($request);
            return new ApprovalRequestResponse($request);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}