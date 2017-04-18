<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

interface TagRepository
{
    public function findAll(): array;
}
