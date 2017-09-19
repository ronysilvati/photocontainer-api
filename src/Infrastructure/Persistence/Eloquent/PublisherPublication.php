<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventNotification;

class PublisherPublication extends EloquentModel
{
    protected $table = 'publisher_publications';

    public function publisher()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
