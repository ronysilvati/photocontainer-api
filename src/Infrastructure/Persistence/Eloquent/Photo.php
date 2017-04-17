<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Photo extends EloquentModel
{
    protected $table = 'photos';

    public function photoFavorite()
    {
        return $this->belongsTo(PhotoFavorite::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
