<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Domain;

interface CepRepository
{
    public function findCep(string $cep): Cep;
    public function findStates(int $country_id);
    public function findCities(int $state_id);
    public function getCountries(): array;
}
