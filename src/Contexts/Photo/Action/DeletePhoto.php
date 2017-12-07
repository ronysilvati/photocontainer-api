<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Command\DeletePhotoCommand;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\EventPhotoHelper;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\DeletedPhotoResponse;

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
     * @param DeletePhotoCommand $command
     * @return DeletedPhotoResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function handle(DeletePhotoCommand $command): DeletedPhotoResponse
    {
        $photo = $this->dbRepo->deletePhoto($command->getGuid());
        $this->photoHelper->deletePhoto($photo);

        return new DeletedPhotoResponse($photo);
    }
}
