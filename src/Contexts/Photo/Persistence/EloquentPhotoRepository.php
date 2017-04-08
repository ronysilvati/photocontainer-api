<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Photo as PhotoModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Download as DownloadModel;
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

    public function download(Download $download): Download
    {
        try {
            $downloadModel = new DownloadModel();
            $downloadModel->event_id = $download->getEventId();
            $downloadModel->user_id = $download->getUserId();
            $downloadModel->save();

            $download->changeId($downloadModel->id);

            return $download;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function find(int $id): Photo
    {
        try {
            $photoData = PhotoModel::find($id);
            $photo = new Photo($photoData->id, $photoData->event_id, null, $photoData->filename);
            return $photo;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }    }
}