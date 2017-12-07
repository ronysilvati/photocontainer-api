<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Command\UpdateSuppliersCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\SuppliersUpdateResponse;

class UpdateSuppliers
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * UpdateSuppliers constructor.
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateSuppliersCommand $command
     * @return SuppliersUpdateResponse
     */
    public function handle(UpdateSuppliersCommand $command): SuppliersUpdateResponse
    {
        $result = $this->repository->saveEventSuppliers($command->getSuppliers(), $command->getEventId());
        return new SuppliersUpdateResponse($result);
    }
}
