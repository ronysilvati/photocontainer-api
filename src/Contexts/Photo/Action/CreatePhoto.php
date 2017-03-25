<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Action;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\User\Response\DomainExceptionResponse;

class CreatePhoto
{
    private $dbRepo;
    private $fsRepo;

    /**
     * CreatePhoto constructor.
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
                }catch (\Exception $e) {
                    $this->fsRepo->rollback($item);
                }
            }
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}