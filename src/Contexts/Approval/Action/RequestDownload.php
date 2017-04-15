<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\DownloadRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class RequestDownload
{
    protected $repository;

    public function __construct(ApprovalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $event_id, int $user_id)
    {
        try {
            $dlRequest = $this->repository->findDownloadRequest($event_id, $user_id);
            if ($dlRequest) {
                throw new \Exception('Pedido já realizado.');
            }

            $dlRequest = new DownloadRequest(
                null,
                $event_id,
                $user_id,
                false,
                false,
                true
            );

            $event = $this->repository->createDownloadRequest($dlRequest);
            return new DownloadRequestResponse($event);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}