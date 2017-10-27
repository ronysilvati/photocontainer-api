<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class AccessLog extends EloquentModel
{
    protected $table = 'access_logs';
}