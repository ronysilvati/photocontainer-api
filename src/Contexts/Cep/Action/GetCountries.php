<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Command\GetCountriesCommand;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\CountryCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;

class GetCountries
{
    /**
     * @var CepRepository
     */
    protected $repository;

    /**
     * @var CacheHelper
     */
    private $cacheHelper;

    /**
     * GetCountries constructor.
     * @param CepRepository $repository
     * @param CacheHelper $cacheHelper
     */
    public function __construct(CepRepository $repository, CacheHelper $cacheHelper)
    {
        $this->repository = $repository;
        $this->cacheHelper = $cacheHelper;
    }

    /**
     * @param GetCountriesCommand $command
     * @return CountryCollectionResponse
     */
    public function handle(GetCountriesCommand $command): CountryCollectionResponse
    {
        $states = $this->cacheHelper->remember(
            'countries',
            function () {
                return $this->repository->getCountries();
            },
            48000
        );

        return new CountryCollectionResponse($states);
    }
}
