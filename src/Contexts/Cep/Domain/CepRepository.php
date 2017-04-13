<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Domain;

interface CepRepository
{
    public function findCep(Cep $cep);
    public function findStates(Cep $cep);
    public function findCities(Cep $cep);
    public function getCountries(): array;
}