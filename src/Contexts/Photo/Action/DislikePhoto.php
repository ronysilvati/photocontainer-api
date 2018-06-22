<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DislikeResponse;


class DislikePhoto
{
    protected $repository;

    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Like $like): \PhotoContainer\PhotoContainer\Contexts\Photo\Response\DislikeResponse
    {
        $like = $this->repository->dislike($like);
        return new DislikeResponse($like);
    }
}
