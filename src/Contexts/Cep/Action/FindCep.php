<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;



use PhotoContainer\PhotoContainer\Contexts\Cep\Persistence\RestCepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\CepResponse;

class FindCep
{
    /**
     * @var RestCepRepository
     */
    protected $repository;

    /**
     * FindCep constructor.
     * @param RestCepRepository $repository
     */
    public function __construct(RestCepRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $zipmail
     * @return CepResponse
     */
    public function handle(string $zipmail): \PhotoContainer\PhotoContainer\Contexts\Cep\Response\CepResponse
    {
        $cep = $this->repository->findCep($zipmail);
        return new CepResponse($cep);
    }
}
