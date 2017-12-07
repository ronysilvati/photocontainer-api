<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Command\DownloadPhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DownloadResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DownloadPhoto
{
    /**
     * @var PhotoRepository
     */
    private $dbRepo;

    /**
     * DownloadPhoto constructor.
     * @param PhotoRepository $dbRepo
     */
    public function __construct(PhotoRepository $dbRepo)
    {
        $this->dbRepo = $dbRepo;
    }

    /**
     * @param DownloadPhotoCommand $command
     * @return DownloadResponse
     */
    public function handle(DownloadPhotoCommand $command)
    {
        $photo = $this->dbRepo->find($command->getPhotoId());
        $download = new Download(null, $command->getUserId(), $photo);

        $this->dbRepo->download($download);

        return new DownloadResponse($download);
    }
}
