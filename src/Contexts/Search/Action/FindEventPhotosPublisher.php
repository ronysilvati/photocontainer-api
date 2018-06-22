<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\EventResponse;


class FindEventPhotosPublisher
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id, int $user_id): \PhotoContainer\PhotoContainer\Contexts\Search\Response\EventResponse
    {
        $result = $this->repository->findEventPhotosPublisher($id, $user_id);
        return new EventResponse($result, 'gallery_photos_publisher');
    }
}
