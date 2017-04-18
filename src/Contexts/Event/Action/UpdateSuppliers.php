<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\SuppliersUpdateResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\DomainExceptionResponse;

class UpdateSuppliers
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $suppliers, int $id)
    {
        try {
            $result = $this->repository->saveEventSuppliers(json_encode((object) $suppliers), $id);
            return new SuppliersUpdateResponse($result);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
