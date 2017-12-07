<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Command\DislikePhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DislikeResponse;


class DislikePhoto
{
    protected $repository;

    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DislikePhotoCommand $command): DislikeResponse
    {
        $like = $this->repository->dislike($command->getLike());
        return new DislikeResponse($like);
    }
}
