<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DislikeResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DislikePhoto
{
    protected $repository;

    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Like $like)
    {
        $like = $this->repository->dislike($like);
        return new DislikeResponse($like);
    }
}
