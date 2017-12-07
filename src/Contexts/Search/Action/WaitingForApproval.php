<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Command\WaitingForApprovalCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\ApprovalCollectionResponse;

class WaitingForApproval
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * WaitingForApproval constructor.
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param WaitingForApprovalCommand $command
     * @return ApprovalCollectionResponse
     */
    public function handle(WaitingForApprovalCommand $command): ApprovalCollectionResponse
    {
        $waitingList = $this->repository->findWaitingRequests($command->getPhotographerId());
        return new ApprovalCollectionResponse($waitingList);
    }
}
