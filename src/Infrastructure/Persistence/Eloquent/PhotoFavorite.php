<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class PhotoFavorite extends EloquentModel
{
    protected $table = 'photo_favorites';
}
