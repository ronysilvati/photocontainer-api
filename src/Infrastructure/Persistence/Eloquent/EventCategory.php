<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class EventCategory extends EloquentModel
{
    protected $fillable = ['event_id', 'category_id'];
    protected $table = 'event_categories';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
