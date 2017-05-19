<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\City;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Country;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\State;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use Whoops\Example\Exception;

class EloquentCepRepository implements CepRepository
{
    /**
     * @var EloquentDatabaseProvider
     */
    private $conn;

    public function __construct(EloquentDatabaseProvider $conn)
    {
        $this->conn = $conn;
    }

    public function findCep(Cep $cep)
    {
        throw new \Exception("Nâo implementado.");
    }

    public function findStates(Cep $cep): array
    {
        try {
            $states = State::where('country_id', $cep->getCountry())->get(['id', 'name', 'statecode'])->toArray();
            return $states;
        } catch (\Exception $e) {
            throw new PersistenceException("Estados não encontrados.", $e->getMessage());
        }
    }

    public function findCities(Cep $cep)
    {
        try {
            $cities = City::where('state_id', $cep->getState())->get(['name'])->toArray();
            return $cities;
        } catch (\Exception $e) {
            throw new PersistenceException("Cidades não encontradas.", $e->getMessage());

        }
    }

    public function getCountries(): array
    {
        try {
            return Country::orderBy('name')->get(['id', 'name'])->toArray();
        } catch (Exception $e) {
            throw new PersistenceException("Países não encontrados.", $e->getMessage());
        }
    }
}
