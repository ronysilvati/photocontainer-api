<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class AccessLog extends EloquentModel
{
    protected $table = 'access_logs';
}