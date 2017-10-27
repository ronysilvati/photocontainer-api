<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class RequestPassword extends EloquentModel
{
    protected $table = 'request_passwords';
}