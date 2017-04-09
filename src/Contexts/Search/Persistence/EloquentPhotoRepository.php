<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearchPublisherDownload;

class EloquentPhotoRepository implements PhotoRepository
{
    public function searchDownloaded(int $user_id)
    {
        $data = EventSearchPublisherDownload::where('user_id', $user_id)->get();

        return $data->map(function ($item){
            return new Download($item->photo_id, $item->user_id, $item->event_id, $item->filename);
        })->toArray();
    }
}