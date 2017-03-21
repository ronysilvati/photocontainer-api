<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\DomainExceptionResponse;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventCreatedResponse;

class CreateEvent
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Event $event)
    {
        try {
            $event->changePhotographer($this->repository->findPhotographer($event->getPhotographer()));
            $this->repository->create($event);

            return new EventCreatedResponse($event);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}