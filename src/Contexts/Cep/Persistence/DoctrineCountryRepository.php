<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Persistence;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CountryRepository;

class DoctrineCountryRepository extends EntityRepository implements CountryRepository
{
    public function getCountries(): array
    {
        $collection = new ArrayCollection($this->findAll());
        return $collection->toArray();
    }
}