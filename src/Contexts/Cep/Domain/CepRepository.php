<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Domain;

interface CepRepository
{
    /**
     * @param string $cep
     * @return Cep
     */
    public function findCep(string $cep): Cep;
}
