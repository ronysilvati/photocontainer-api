<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\NoContentResponse;

class SetPhotoAsCover
{
    protected $repository;

    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(string $guid): \PhotoContainer\PhotoContainer\Infrastructure\NoContentResponse
    {
        $this->repository->setAsAlbumCover($guid);
        return new NoContentResponse();
    }
}
