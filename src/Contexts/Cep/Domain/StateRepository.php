<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Domain;

interface StateRepository
{
    /**
     * @param int $country_id
     * @return mixed
     */
    public function findStates(int $country_id): array;
}
