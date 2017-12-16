<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Domain;

interface CountryRepository
{
    /**
     * @return array
     */
    public function getCountries(): array;
}
