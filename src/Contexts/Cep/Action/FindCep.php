<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\CepResponse;

class FindCep
{
    protected $repository;

    public function __construct(CepRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Cep $cep)
    {
        try {
            $cep = $this->repository->findCep($cep);
            return new CepResponse($cep);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}