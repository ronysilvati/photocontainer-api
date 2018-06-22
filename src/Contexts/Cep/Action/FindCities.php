<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;


use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\StateCollectionResponse;


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
     * @param int $state_id
     * @return StateCollectionResponse
     */
    public function handle(int $state_id): \PhotoContainer\PhotoContainer\Contexts\Cep\Response\StateCollectionResponse
    {
        $states = $this->repository->findCities($state_id);
        return new StateCollectionResponse($states);
    }
}
