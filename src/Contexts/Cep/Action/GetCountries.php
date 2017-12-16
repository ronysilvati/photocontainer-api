<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Command\GetCountriesCommand;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CountryRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\CountryCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;

class GetCountries
{
    /**
     * @var CountryRepository
     */
    protected $repository;

    /**
     * @var CacheHelper
     */
    private $cacheHelper;

    /**
     * GetCountries constructor.
     * @param CountryRepository $repository
     * @param CacheHelper $cacheHelper
     */
    public function __construct(CountryRepository $repository, CacheHelper $cacheHelper)
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
        $countries = $this->cacheHelper->remember(
            'countries',
            function () {
                return $this->repository->getCountries();
            },
            48000
        );

        return new CountryCollectionResponse($countries);
    }
}
