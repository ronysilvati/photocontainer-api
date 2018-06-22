<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Domain;

interface AuthRepository
{
    public function find(string $user);
    public function logAccess(int $user_id);
}
