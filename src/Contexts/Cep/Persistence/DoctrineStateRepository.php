<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Persistence;

use Doctrine\ORM\EntityRepository;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\StateRepository;

class DoctrineStateRepository extends EntityRepository implements StateRepository
{
    public function findStates(int $country_id): array
    {
        return $this->findBy(['countryId' => $country_id]);
    }
}