<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Command\CreatePhotoCommand;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\PhotoResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\EventPhotoHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class CreatePhoto
{
    /**
     * @var PhotoRepository
     */
    private $dbRepo;

    /**
     * @var EventPhotoHelper
     */
    private $fsRepo;

    /**
     * @var CacheHelper
     */
    private $cacheHelper;

    /**
     * CreatePhoto constructor.
     * @param PhotoRepository $dbRepo
     * @param EventPhotoHelper $fsRepo
     * @param CacheHelper $cacheHelper
     */
    public function __construct(
        PhotoRepository $dbRepo,
        EventPhotoHelper $fsRepo,
        CacheHelper $cacheHelper
    ) {
        $this->dbRepo = $dbRepo;
        $this->fsRepo = $fsRepo;
        $this->cacheHelper = $cacheHelper;
    }

    /**
     * @param CreatePhotoCommand $command
     * @return PhotoResponse|DomainExceptionResponse
     */
    public function handle(CreatePhotoCommand $command)
    {
        foreach ($command->getPhotos() as $item) {
            try {
                $eventPhotos = $this->dbRepo->findEventPhotos($command->getEventId());
                if (\count($eventPhotos) >= 30) {
                    return new DomainExceptionResponse('O limite de fotos foi atingido.');
                }

                $this->fsRepo->create($item);
                $this->dbRepo->create($item);
            } catch (\Exception $e) {
                return new DomainExceptionResponse($e->getMessage());
            }
        }

        $this->cacheHelper->clearNamespace('find_event');
        $this->dbRepo->activateEvent($command->getEventId());

        return new PhotoResponse($command->getPhotos());
    }
}
