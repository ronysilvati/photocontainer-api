<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\AuthRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User;

class EloquentAuthRepository implements AuthRepository
{
    public function find(string $user)
    {
        $user = User::where('email', $user)->first();

        if ($user === null) {
            throw new PersistenceException("Usuário inexistente.");
        }

        return $user;
    }
}
