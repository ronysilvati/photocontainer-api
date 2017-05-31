<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\FSPhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DeletedPhotoResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DeletePhoto
{
    private $dbRepo;
    private $fsRepo;

    /**
     * DeletePhoto constructor.
     * @param PhotoRepository $dbRepo
     * @param FSPhotoRepository $fsRepo
     */
    public function __construct(PhotoRepository $dbRepo, FSPhotoRepository $fsRepo)
    {
        $this->dbRepo = $dbRepo;
        $this->fsRepo = $fsRepo;
    }

    /**
     * @param string $guid
     * @return DeletedPhotoResponse|DomainExceptionResponse
     */
    public function handle(string $guid)
    {
        $photo = $this->dbRepo->deletePhoto($guid);
        $this->fsRepo->deletePhoto($photo);

        return new DeletedPhotoResponse($photo);
    }
}
