<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class User extends EloquentModel
{
    protected $table = 'users';

    public function detail()
    {
        return $this->hasOne(Detail::class);
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
