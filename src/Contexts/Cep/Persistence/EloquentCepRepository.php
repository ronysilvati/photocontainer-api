<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\City;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Country;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\State;

class EloquentCepRepository implements CepRepository
{
    /**
     * @param string $zipcode
     * @return Cep
     */
    public function findCep(string $zipcode): Cep
    {
        throw new \RuntimeException('Nâo implementado.');
    }

    /**
     * @param int $country_id
     * @return array
     * @throws PersistenceException
     */
    public function findStates(int $country_id): array
    {
        try {
            return State::where('country_id', $country_id)->get(['id', 'name', 'statecode'])->toArray();
        } catch (\Exception $e) {
            throw new PersistenceException('Estados não encontrados.', $e->getMessage());
        }
    }

    /**
     * @param int $state_id
     * @return array
     * @throws PersistenceException
     */
    public function findCities(int $state_id): array
    {
        try {
            return City::where('state_id', $state_id)->get(['name'])->toArray();
        } catch (\Exception $e) {
            throw new PersistenceException('Cidades não encontradas.', $e->getMessage());
        }
    }

    /**
     * @return array
     * @throws PersistenceException
     */
    public function getCountries(): array
    {
        try {
            return Country::orderBy('name')->get(['id', 'name'])->toArray();
        } catch (\Exception $e) {
            throw new PersistenceException('Países não encontrados.', $e->getMessage());
        }
    }
}
