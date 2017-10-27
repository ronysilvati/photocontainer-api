<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Download extends EloquentModel
{
    protected $table = 'downloads';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
