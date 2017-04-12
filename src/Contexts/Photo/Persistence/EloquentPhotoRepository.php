<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Persistence;

use League\Flysystem\Exception;
use PhotoContainer\PhotoContainer\Contexts\Photo\Action\LikePhoto;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Photo as PhotoModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Download as DownloadModel;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\PhotoFavorite;

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
            $downloadModel->photo_id = $download->getPhoto()->getId();
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

            if ($photoData == null) {
                throw new \Exception("A foto nâo existe.");
            }

            $photo = new Photo($photoData->id, $photoData->event_id, null);
            $photo->setPhysicalName($photoData->filename);

            return $photo;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function like(Like $like): Like
    {
        try {
             $liked = PhotoFavorite::where('photo_id', $like->getPhotoId())
                ->where('user_id', $like->getUserId())
                ->count();

             if ($liked > 0) {
                throw new \Exception('Foto já é favorita');
             }

            $model = new PhotoFavorite();
            $model->photo_id = $like->getPhotoId();
            $model->user_id = $like->getUserId();
            $model->save();

            return $like;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function dislike(Like $like): Like
    {
        try {
            $ok = PhotoFavorite::where('photo_id', $like->getPhotoId())
                ->where('user_id', $like->getUserId())
                ->delete();

            if ($ok == false) {
                throw new Exception('Não foi possível remover favorito.');
            }

            return $like;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }
}