<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\ApprovalCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class WaitingForApproval
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $photographer_id)
    {
        $waitingList = $this->repository->findWaitingRequests($photographer_id);
        return new ApprovalCollectionResponse($waitingList);
    }
}
