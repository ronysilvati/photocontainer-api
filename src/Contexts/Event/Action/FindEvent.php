<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Search;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\DomainExceptionResponse;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventCollectionResponse;

class FindEvent
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Search $search)
    {
        try {
            $result = $this->repository->search($search);
            return new EventCollectionResponse($result);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}