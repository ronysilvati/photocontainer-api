<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;

class FilesystemPhotoRepository implements PhotoRepository
{
    public function create(Photo $photo): Photo
    {
        try {
            // temp pra pasta final
            $photo->changePhysicalName("lololo.jpg");
            return $photo;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function rollback(Photo $photo)
    {
//        unlink($photo->getPhysicalName());
    }
}