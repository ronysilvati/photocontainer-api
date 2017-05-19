<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventRemovedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DeleteEvent
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * DeleteEvent constructor.
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return EventRemovedResponse
     */
    public function handle(int $id)
    {
        $this->repository->delete($id);
        return new EventRemovedResponse($id);
    }
}
