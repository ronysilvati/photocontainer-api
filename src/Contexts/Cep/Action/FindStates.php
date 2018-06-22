<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;


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
     * @param int $coutry_id
     * @return StateCollectionResponse
     */
    public function handle(int $coutry_id): \PhotoContainer\PhotoContainer\Contexts\Cep\Response\StateCollectionResponse
    {
        $states = $this->repository->findStates($coutry_id);
        return new StateCollectionResponse($states);
    }
}
