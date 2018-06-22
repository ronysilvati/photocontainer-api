<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Detail extends EloquentModel
{
    protected $table = 'user_details';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
