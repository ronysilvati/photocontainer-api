<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Command\FindStatesCommand;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\StateRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\StateCollectionResponse;

class FindStates
{
    /**
     * @var StateRepository
     */
    protected $repository;

    /**
     * FindStates constructor.
     * @param StateRepository $repository
     */
    public function __construct(StateRepository $repository)
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
