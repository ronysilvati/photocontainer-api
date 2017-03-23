<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface CategoryRepository
{
    public function findAll(): array;
}