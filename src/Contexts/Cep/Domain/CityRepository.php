<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Domain;

interface CityRepository
{
    /**
     * @param int $state_id
     * @return mixed
     */
    public function findCities(int $state_id): ?array;
}
