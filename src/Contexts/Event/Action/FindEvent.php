<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventFoundResponse;

class FindEvent
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * FindEvent constructor.
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return EventFoundResponse
     */
    public function handle(int $id): \PhotoContainer\PhotoContainer\Contexts\Event\Response\EventFoundResponse
    {
        $event = $this->repository->find($id);
        return new EventFoundResponse($event);
    }
}
