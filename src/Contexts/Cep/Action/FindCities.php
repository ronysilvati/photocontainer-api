<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\StateCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class FindCities
{
    /**
     * @var CepRepository
     */
    protected $repository;

    /**
     * FindCities constructor.
     * @param CepRepository $repository
     */
    public function __construct(CepRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Cep $cep
     * @return StateCollectionResponse
     */
    public function handle(Cep $cep)
    {
        $states = $this->repository->findCities($cep);
        return new StateCollectionResponse($states);
    }
}
