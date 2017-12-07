<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Command\DeleteEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventRemovedResponse;


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
     * @param DeleteEventCommand $command
     * @return EventRemovedResponse
     */
    public function handle(DeleteEventCommand $command): EventRemovedResponse
    {
        $this->repository->delete($command->getEventId());
        return new EventRemovedResponse($command->getEventId());
    }
}
