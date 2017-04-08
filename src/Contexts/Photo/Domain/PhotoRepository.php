<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

interface PhotoRepository
{
    public function find(int $id): Photo;
    public function create(Photo $conf): Photo;
}