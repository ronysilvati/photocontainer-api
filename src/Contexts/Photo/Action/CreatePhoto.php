<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

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
     * @param PhotoRepository $fsRepo
     */
    public function __construct(PhotoRepository $dbRepo, PhotoRepository $fsRepo)
    {
        $this->dbRepo = $dbRepo;
        $this->fsRepo = $fsRepo;
    }

    public function handle(array $array, int $event_id)
    {
        try {
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
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
