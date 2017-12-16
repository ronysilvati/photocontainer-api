<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Persistence;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CityRepository;

class DoctrineCityRepository extends EntityRepository implements CityRepository
{
    public function findCities(int $state_id): ?array
    {
        $collection = new ArrayCollection($this->findBy(['stateId' => $state_id]));
        return $collection ? $collection->toArray() : null;
    }
}