<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Action;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Response\CountryCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class GetCountries
{
    /**
     * @var CepRepository
     */
    protected $repository;

    /**
     * GetCountries constructor.
     * @param CepRepository $repository
     */
    public function __construct(CepRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return CountryCollectionResponse|DomainExceptionResponse
     */
    public function handle()
    {
        try {
            $states = $this->repository->getCountries();
            return new CountryCollectionResponse($states);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
