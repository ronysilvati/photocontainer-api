<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class TagCategory extends EloquentModel
{
    protected $table = 'tag_categories';

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
