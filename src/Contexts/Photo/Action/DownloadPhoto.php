<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DownloadPhoto
{
    private $dbRepo;
    private $fsRepo;

    /**
     * DownloadPhoto constructor.
     * @param PhotoRepository $dbRepo
     * @param PhotoRepository $fsRepo
     */
    public function __construct(PhotoRepository $dbRepo, PhotoRepository $fsRepo)
    {
        $this->dbRepo = $dbRepo;
        $this->fsRepo = $fsRepo;
    }

    /**
     * @param int $photo_id
     * @param int $user_id
     * @return PhotoResponse|DomainExceptionResponse
     */
    public function handle(int $photo_id, int $user_id)
    {
        try {
            $photo = $this->dbRepo->find($photo_id);

            $download = new Download(null, $user_id, $photo);

            $this->fsRepo->download($download);

            exit;
            $this->dbRepo->download($download);

            exit;

            return new PhotoResponse($download);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}