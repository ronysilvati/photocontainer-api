<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;


use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventUpdateResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;
use Whoops\Example\Exception;

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
    public function handle(int $id, array $data)
    {
        $event = $this->repository->find($id);
        $this->repository->update($id, $data, $event);

        return new EventUpdateResponse($event);
    }
}
