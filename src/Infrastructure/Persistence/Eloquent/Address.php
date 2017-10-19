<?php
declare(strict_types=1);

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Address extends EloquentModel
{
    protected $table = 'address';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
