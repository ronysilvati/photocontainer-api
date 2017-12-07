<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Command\FindEventPhotosPublisherCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\EventResponse;


class FindEventPhotosPublisher
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FindEventPhotosPublisherCommand $command): EventResponse
    {
        $result = $this->repository->findEventPhotosPublisher($command->getEventId(), $command->getUserId());
        return new EventResponse($result, 'gallery_photos_publisher');
    }
}
