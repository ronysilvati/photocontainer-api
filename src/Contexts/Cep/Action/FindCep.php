<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\CepResponse;


class FindCep
{
    /**
     * @var CepRepository
     */
    protected $repository;

    /**
     * FindCep constructor.
     * @param CepRepository $repository
     */
    public function __construct(CepRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Cep $cep
     * @return CepResponse
     */
    public function handle(Cep $cep)
    {
        $cep = $this->repository->findCep($cep);
        return new CepResponse($cep);
    }
}
