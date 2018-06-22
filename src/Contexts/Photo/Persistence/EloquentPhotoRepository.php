<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Persistence;

use Illuminate\Database\Capsule\Manager as DB;
use League\Flysystem\Exception;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\SelectedPhotos;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Download as DownloadModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Photo as PhotoModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\PhotoFavorite;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User;


class EloquentPhotoRepository implements PhotoRepository
{
    /**
     * @param Photo $photo
     * @return Photo
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
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
            throw new PersistenceException('Foto não criada.', $e->getMessage());
        }
    }

    /**
     * @param Download $download
     * @return Download
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
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
            throw new PersistenceException('Download não realizado.', $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return Photo
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function find(int $id): Photo
    {
        try {
            $photoData = PhotoModel::find($id);

            if ($photoData == null) {
                throw new \RuntimeException('A foto não existe.');
            }

            $photo = new Photo($photoData->id, $photoData->event_id, null);
            $photo->setPhysicalName($photoData->filename);

            return $photo;
        } catch (\Exception $e) {
            throw new PersistenceException('A foto não existe.', $e->getMessage());
        }
    }

    /**
     * @param Like $like
     * @return Like
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function like(Like $like): Like
    {
        try {
            $liked = PhotoFavorite::where('photo_id', $like->getPhotoId())
                ->where('user_id', $like->getUserId())
                ->count();

            if ($liked > 0) {
                throw new \RuntimeException('Foto já é favorita');
            }

            $model = new PhotoFavorite();
            $model->photo_id = $like->getPhotoId();
            $model->user_id = $like->getUserId();
            $model->save();

            return $like;
        } catch (\Exception $e) {
            throw new PersistenceException('Foto já é favorita', $e->getMessage());
        }
    }

    /**
     * @param Like $like
     * @return Like
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
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
            throw new PersistenceException('Não foi possível remover favorito.', $e->getMessage());
        }
    }

    /**
     * @param Photo $photo
     * @return Photographer
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function findPhotoOwner(Photo $photo): Photographer
    {
        try {
            $user = Event::find($photo->getEventId())->with('User')->first()->toArray();
            return new Photographer($user['user']['name'], $user['user']['email']);
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível encontrar o dono da foto.', $e->getMessage());
        }
    }

    /**
     * @param int $publisher_id
     * @return Publisher
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function findPublisher(int $publisher_id): Publisher
    {
        try {
            $user = User::find($publisher_id)->toArray();
            return new Publisher($user['name'], $user['email']);
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível encontrar o publisher.', $e->getMessage());
        }
    }

    /**
     * @param string $guid
     * @return Photo
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function deletePhoto(string $guid): Photo
    {
        try {
            DB::beginTransaction();

            $photo = PhotoModel::where('filename', 'like', "{$guid}.%");

            $photoData = $photo->first();

            if (DownloadModel::where('photo_id', $photoData->id)->count() > 0) {
                throw new \RuntimeException('Algum publisher já baixou essa foto, ela não pode ser removida.');
            }

            PhotoFavorite::where('photo_id', $photoData->id)->delete();
            $photo->delete();

            DB::commit();

            $photoDomain = new Photo($photoData->id, $photoData->event_id);
            $photoDomain->setPhysicalName($photoData->filename);

            return $photoDomain;
        } catch (\Exception $e) {
            DB::rollback();
            throw new PersistenceException($e->getMessage(), $e->getMessage());
        }
    }

    /**
     * @param int $event_id
     * @return array|null
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function findEventPhotos(int $event_id): ?array
    {
        try {
            return PhotoModel::where('event_id', $event_id)->get()->toArray();
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível encontrar fotos do evento.', $e->getMessage());
        }
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     * @return null|SelectedPhotos
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function selectAllPhotos(int $event_id, int $publisher_id): ?SelectedPhotos
    {
        try {
            $photosEvent = PhotoModel::where('event_id', $event_id)->get()->toArray();
            $selected = new SelectedPhotos($publisher_id, $event_id);

            return $this->convertToDomainModel($selected, $photosEvent);
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível encontrar fotos do evento.', $e->getMessage());
        }
    }

    /**
     * @param array $photo_ids
     * @param int $publisher_id
     * @return null|SelectedPhotos
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function selectPhotos(array $photo_ids, int $publisher_id): ?SelectedPhotos
    {
        try {
            $photosEvent = PhotoModel::whereIn('id', $photo_ids)->get()->toArray();
            $selected = new SelectedPhotos($publisher_id);

            return $this->convertToDomainModel($selected, $photosEvent);
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível encontrar fotos do evento.', $e->getMessage());
        }
    }

    /**
     * @param SelectedPhotos $selected
     * @param array|null $photosEvent
     * @return null|SelectedPhotos
     */
    public function convertToDomainModel(SelectedPhotos $selected, ?array $photosEvent): ?SelectedPhotos
    {
        if (count($photosEvent) == 0) {
            return null;
        }

        foreach ($photosEvent as $photo) {
            $photoModel = new Photo($photo['id'], $photo['event_id'], null);
            $photoModel->setPhysicalName($photo['filename']);

            $selected->add($photoModel);
        }

        return $selected;
    }

    /**
     * @param string $guid
     * @return bool
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function setAsAlbumCover(string $guid): bool
    {
        try {
            $photoModel = PhotoModel::where('filename', 'like', "{$guid}.%")->first();

            if ($photoModel->cover) {
                return true;
            }

            PhotoModel::where('event_id', $photoModel->event_id)->update(['cover' => 0]);

            $photoModel->cover = 1;
            $photoModel->save();

            return true;
        } catch (\Exception $e) {
            throw new PersistenceException('Não foi possível configurar a foto como capa.', $e->getMessage());
        }
    }

    public function activateEvent(int $event_id): bool
    {
        $event = Event::find($event_id);
        $event->active = 1;
        $event->save();

        return true;
    }
}
