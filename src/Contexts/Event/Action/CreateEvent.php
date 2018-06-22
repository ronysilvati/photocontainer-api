<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventCreatedResponse;


class CreateEvent
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(EventRepository $repository, UserRepository $userRepo)
    {
        $this->repository = $repository;
        $this->userRepo = $userRepo;
    }

    /**
     * @param Event $event
     * @return EventCreatedResponse
     */
    public function handle(Event $event): \PhotoContainer\PhotoContainer\Contexts\Event\Response\EventCreatedResponse
    {
        $event->changePhotographer($this->userRepo->findPhotographer($event->getPhotographer()));
        $this->repository->create($event);

        return new EventCreatedResponse($event);
    }
}
