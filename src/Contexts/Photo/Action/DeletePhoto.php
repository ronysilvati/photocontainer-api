<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Infrastructure\Helper\EventPhotoHelper;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DeletedPhotoResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DeletePhoto
{
    /**
     * @var PhotoRepository
     */
    private $dbRepo;

    /**
     * @var EventPhotoHelper
     */
    private $photoHelper;

    /**
     * DeletePhoto constructor.
     * @param PhotoRepository $dbRepo
     * @param EventPhotoHelper $photoHelper
     */
    public function __construct(PhotoRepository $dbRepo, EventPhotoHelper $photoHelper)
    {
        $this->dbRepo = $dbRepo;
        $this->photoHelper = $photoHelper;
    }

    /**
     * @param string $guid
     * @return DeletedPhotoResponse|DomainExceptionResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function handle(string $guid)
    {
        $photo = $this->dbRepo->deletePhoto($guid);
        $this->photoHelper->deletePhoto($photo);

        return new DeletedPhotoResponse($photo);
    }
}
