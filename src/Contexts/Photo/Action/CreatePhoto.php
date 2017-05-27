<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\FSPhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\PhotoResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class CreatePhoto
{
    private $dbRepo;
    private $fsRepo;

    /**
     * CreatePhoto constructor.
     * @param PhotoRepository $dbRepo
     * @param FSPhotoRepository $fsRepo
     */
    public function __construct(PhotoRepository $dbRepo, FSPhotoRepository $fsRepo)
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
                $this->fsRepo->rollback($item);
                return new DomainExceptionResponse($e->getMessage());
            }
        }
        return new PhotoResponse($array);
    }
}
