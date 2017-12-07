<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Command\FindStatesCommand;
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
     * @param FindStatesCommand $command
     * @return StateCollectionResponse
     */
    public function handle(FindStatesCommand $command): StateCollectionResponse
    {
        $states = $this->repository->findStates($command->getCountryId());
        return new StateCollectionResponse($states);
    }
}
