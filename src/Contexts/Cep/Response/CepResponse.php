<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Response;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;

class CepResponse implements \JsonSerializable
{
    private $cep;

    public function __construct(Cep $cep)
    {
        $this->cep = $cep;
    }

    public function jsonSerialize()
    {
        return [
            'zipcode' => $this->cep->getZipcode(),
            'country' => $this->cep->getCountry(),
            'state' => $this->cep->getState(),
            'city' => $this->cep->getCity(),
            'neighborhood' => $this->cep->getNeighborhood(),
            'street' => $this->cep->getStreet(),
            'complement' => $this->cep->getComplement(),
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
