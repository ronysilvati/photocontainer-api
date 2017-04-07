<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Photo as PhotoModel;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;

class EloquentPhotoRepository implements PhotoRepository
{
    public function create(Photo $photo): Photo
    {
        try {
            $photoModel = new PhotoModel();
            $photoModel->event_id = $photo->getEventId();
            $photoModel->filename = $photo->getPhysicalName();
            $photoModel->save();

            $photo->changeId($photoModel->id);

            return $photo;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

}