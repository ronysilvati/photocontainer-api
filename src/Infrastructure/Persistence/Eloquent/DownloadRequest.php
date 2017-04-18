<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DownloadRequest extends EloquentModel
{
    protected $table = 'download_requests';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
