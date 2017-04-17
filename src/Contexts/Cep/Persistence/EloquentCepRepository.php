<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\City;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Country;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\State;

class EloquentCepRepository implements CepRepository
{
    public function findCep(Cep $cep)
    {
        throw new \Exception("Nâo implementado.");
    }

    public function findStates(Cep $cep): array
    {
        $states = State::where('country_id', $cep->getCountry())->get(['id', 'name', 'statecode'])->toArray();
        return $states;
    }

    public function findCities(Cep $cep)
    {
        $cities = City::where('state_id', $cep->getState())->get(['name'])->toArray();
        return $cities;
    }

    public function getCountries(): array
    {
        return Country::orderBy('name')->get(['id', 'name'])->toArray();
    }
}
