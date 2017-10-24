<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\AuthRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\AccessLog;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User;


class EloquentAuthRepository implements AuthRepository
{
    /**
     * @param string $user
     * @return string
     * @throws PersistenceException
     */
    public function find(string $user)
    {
        try {
            $user = User::where('email', $user)->first();

            if ($user === null) {
                throw new \RuntimeException('Usuário inexistente.');
            }

            return $user;
        } catch (\Exception $e) {
            throw new PersistenceException('Usuário não encontrado', $e->getMessage());
        }
    }

    /**
     * @param int $user_id
     * @throws PersistenceException
     */
    public function logAccess(int $user_id): void
    {
        try {
            $log = new AccessLog();
            $log->user_id = $user_id;
            $log->save();
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na criação de log.', $e->getMessage());
        }
    }
}
