<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Helper;

use Ramsey\Uuid\Uuid;

class TokenGeneratorHelper
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}