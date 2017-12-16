<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Response;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Country;

class CountryCollectionResponse extends StateCollectionResponse
{
    public function jsonSerialize()
    {
        return array_map(function (Country $value) {
            return ['id' => $value->getId(), 'name' => utf8_encode($value->getName())];
        }, $this->collection);
    }
}
