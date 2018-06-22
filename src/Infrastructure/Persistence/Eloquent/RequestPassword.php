<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class RequestPassword extends EloquentModel
{
    protected $table = 'request_passwords';
}