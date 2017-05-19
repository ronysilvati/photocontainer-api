<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventSearch;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\EventCollectionResponse;


class FindEvent
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(EventSearch $search)
    {
        $result = $this->repository->find($search);
        return new EventCollectionResponse($result);
    }
}
