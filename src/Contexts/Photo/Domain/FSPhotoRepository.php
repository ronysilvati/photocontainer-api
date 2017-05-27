<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

interface FSPhotoRepository
{
    public function create(Photo $photo): Photo;
    public function deletePhoto(Photo $photo);
    public function rollback(Photo $photo);
}