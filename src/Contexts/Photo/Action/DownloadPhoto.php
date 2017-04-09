<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DownloadResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\ImageHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DownloadPhoto
{
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
     * @param int $user_id
     * @return DownloadResponse|DomainExceptionResponse
     */
    public function handle(int $photo_id, int $user_id)
    {
        try {
            $photo = $this->dbRepo->find($photo_id);
            $download = new Download(null, $user_id, $photo);

            $this->dbRepo->download($download);

            return new DownloadResponse($download);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}