<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\AuthRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\AccessLog;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;

class EloquentAuthRepository implements AuthRepository
{
    /**
     * @var EloquentDatabaseProvider
     */
    private $conn;

    public function __construct(EloquentDatabaseProvider $conn)
    {
        $this->conn = $conn;
    }

    public function find(string $user)
    {
        $user = User::where('email', $user)->first();

        if ($user === null) {
            throw new PersistenceException("Usuário inexistente.");
        }

        return $user;
    }

    public function logAccess(int $user_id)
    {
        try {
            $log = new AccessLog();
            $log->user_id = $user_id;
            $log->save();
        } catch (\Exception $e) {
            throw new PersistenceException("Erro na criação de log.");
        }
    }
}
