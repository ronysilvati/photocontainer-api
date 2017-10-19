<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class EventFavorite extends EloquentModel
{
    protected $table = 'event_favorites';
}
