<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\DownloadRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class RequestDownload
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $event_id, int $user_id)
    {
        try {
            $dlRequest = $event = $this->repository->findDownloadRequest($event_id, $user_id);
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