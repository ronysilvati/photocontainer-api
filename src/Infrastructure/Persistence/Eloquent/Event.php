<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventNotification;

class Event extends EloquentModel
{
    protected $table = 'events';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventCategory()
    {
        return $this->hasMany(EventCategory::class);
    }

    public function eventTag()
    {
        return $this->hasMany(EventTag::class);
    }

    public function photo()
    {
        return $this->hasMany(Photo::class);
    }

    public function downloadRequest()
    {
        return $this->hasMany(DownloadRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(EventNotification::class);
    }
}
