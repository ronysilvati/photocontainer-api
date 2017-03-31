<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\StateCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class FindCities
{
    protected $repository;

    public function __construct(CepRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Cep $cep)
    {
        try {
            $states = $this->repository->findCities($cep);
            return new StateCollectionResponse($states);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}