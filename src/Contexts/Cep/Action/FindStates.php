<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\StateCollectionResponse;


class FindStates
{
    /**
     * @var CepRepository
     */
    protected $repository;

    /**
     * FindStates constructor.
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
        $states = $this->repository->findStates($cep);
        return new StateCollectionResponse($states);
    }
}
