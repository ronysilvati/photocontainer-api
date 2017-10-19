<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;


use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventUpdateResponse;


class UpdateEvent
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * UpdateEvent constructor.
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @param array $data
     * @return EventUpdateResponse
     */
    public function handle(int $id, array $data): \PhotoContainer\PhotoContainer\Contexts\Event\Response\EventUpdateResponse
    {
        $event = $this->repository->find($id);
        $this->repository->update($id, $data, $event);

        return new EventUpdateResponse($event);
    }
}
