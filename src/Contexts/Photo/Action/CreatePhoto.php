<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\PhotoResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Helper\EventPhotoHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class CreatePhoto
{
    private $dbRepo;
    private $fsRepo;

    /**
     * CreatePhoto constructor.
     * @param PhotoRepository $dbRepo
     * @param EventPhotoHelper $fsRepo
     */
    public function __construct(PhotoRepository $dbRepo, EventPhotoHelper $fsRepo)
    {
        $this->dbRepo = $dbRepo;
        $this->fsRepo = $fsRepo;
    }

    /**
     * @param array $array
     * @param int $event_id
     * @return PhotoResponse|DomainExceptionResponse
     */
    public function handle(array $array, int $event_id)
    {
        foreach ($array as $item) {
            try {
                $eventPhotos = $this->dbRepo->findEventPhotos($event_id);
                if (count($eventPhotos) >= 30) {
                    return new DomainExceptionResponse('O limite de fotos foi atingido.');
                }

                $this->fsRepo->create($item);
                $this->dbRepo->create($item);
            } catch (\Exception $e) {
                return new DomainExceptionResponse($e->getMessage());
            }
        }

        $this->dbRepo->activateEvent($event_id);

        return new PhotoResponse($array);
    }
}
