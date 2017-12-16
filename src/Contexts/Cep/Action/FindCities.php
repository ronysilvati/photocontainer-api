<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Command\FindCitiesCommand;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CityRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\CityCollectionResponse;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\StateCollectionResponse;

class FindCities
{
    /**
     * @var CepRepository
     */
    protected $repository;

    /**
     * FindCities constructor.
     * @param CityRepository $repository
     */
    public function __construct(CityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindCitiesCommand $command
     * @return StateCollectionResponse
     */
    public function handle(FindCitiesCommand $command): StateCollectionResponse
    {
        $states = $this->repository->findCities($command->getStateId());
        return new CityCollectionResponse($states);
    }
}
