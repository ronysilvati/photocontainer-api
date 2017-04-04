<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;

class EloquentPhotoRepository implements PhotoRepository
{
    public function create(Photo $conf): Photo
    {
        // TODO: persistem em banco de dados

        return $conf;
    }

}