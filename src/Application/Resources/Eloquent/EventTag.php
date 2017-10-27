<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class EventTag extends EloquentModel
{
    protected $table = 'event_tags';
    protected $fillable = ['event_id', 'tag_id'];
}
