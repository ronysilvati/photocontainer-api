<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Command\SetPhotoAsCoverCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\NoContentResponse;

class SetPhotoAsCover
{
    /**
     * @var PhotoRepository
     */
    protected $repository;

    /**
     * SetPhotoAsCover constructor.
     * @param PhotoRepository $repository
     */
    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param SetPhotoAsCoverCommand $command
     * @return NoContentResponse
     */
    public function handle(SetPhotoAsCoverCommand $command): NoContentResponse
    {
        $this->repository->setAsAlbumCover($command->getGuid());
        return new NoContentResponse();
    }
}
