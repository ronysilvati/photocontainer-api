<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Response\PhotoResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\DomainExceptionResponse;

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

    public function handle(array $array)
    {
        try {
            foreach ($array as $item) {
                try {
                    $this->fsRepo->create($item);
                    $this->dbRepo->create($item);
                } catch (\Exception $e) {
                    $this->fsRepo->rollback($item);
                }
            }
            return new PhotoResponse($array);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
