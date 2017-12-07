<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Command\UpdateEventCommand;
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
     * @param UpdateEventCommand $command
     * @return EventUpdateResponse
     */
    public function handle(UpdateEventCommand $command): EventUpdateResponse
    {
        $event = $this->repository->find($command->getEventId());
        $this->repository->update($command->getEventId(), $command->getData(), $event);

        return new EventUpdateResponse($event);
    }
}
