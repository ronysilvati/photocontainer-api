<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

interface CategoryRepository
{
    public function findAll(): array;
}
