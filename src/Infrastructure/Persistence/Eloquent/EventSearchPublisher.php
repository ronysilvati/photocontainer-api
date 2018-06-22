<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class EventSearchPublisher extends EloquentModel
{
    protected $table = 'event_search_publisher';
}
