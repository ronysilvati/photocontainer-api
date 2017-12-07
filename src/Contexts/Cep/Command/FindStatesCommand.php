<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Command;

class FindStatesCommand
{
    /**
     * @var int
     */
    private $country_id;

    /**
     * FindStatesCommand constructor.
     * @param int $countryId
     */
    public function __construct(int $countryId)
    {
        $this->country_id = $countryId;
    }

    /**
     * @return int
     */
    public function getCountryId(): int
    {
        return $this->country_id;
    }
}
