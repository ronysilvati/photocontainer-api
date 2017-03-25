<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

interface PhotoRepository
{
    public function create(Photo $conf): Photo;
}