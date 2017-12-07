<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Command;

class FindCepCommand
{
    /**
     * @var string
     */
    private $cep;

    /**
     * FindCepCommand constructor.
     * @param string $cep
     */
    public function __construct(string $cep)
    {
        $this->cep = $cep;
    }

    /**
     * @return string
     */
    public function getCep(): string
    {
        return $this->cep;
    }
}