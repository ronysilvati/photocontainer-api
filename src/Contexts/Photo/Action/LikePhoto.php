<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Command\LikePhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\LikeResponse;


class LikePhoto
{
    protected $repository;

    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(LikePhotoCommand $command): LikeResponse
    {
        $like = $this->repository->like($command->getLike());
        return new LikeResponse($like);
    }
}
