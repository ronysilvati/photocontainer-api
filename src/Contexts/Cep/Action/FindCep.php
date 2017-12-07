<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Command\FindCepCommand;
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
     * @param FindCepCommand $command
     * @return CepResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function handle(FindCepCommand $command): CepResponse
    {
        $cep = $this->repository->findCep($command->getCep());
        return new CepResponse($cep);
    }
}
