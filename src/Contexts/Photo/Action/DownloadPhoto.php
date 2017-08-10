<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

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
     * @param int $photo_id
     * @param int $publisher_id
     * @return DownloadResponse|DomainExceptionResponse
     */
    public function handle(int $photo_id, int $publisher_id)
    {
        $photo = $this->dbRepo->find($photo_id);
        $download = new Download(null, $publisher_id, $photo);

        $this->dbRepo->download($download);

        return new DownloadResponse($download);
    }
}
