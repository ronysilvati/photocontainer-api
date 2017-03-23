<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface TagRepository
{
    public function findAll(): array;
}