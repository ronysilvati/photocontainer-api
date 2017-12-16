<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Persistence;

use Doctrine\ORM\EntityRepository;
use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\AccessLog;
use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\Auth;
use PhotoContainer\PhotoContainer\Contexts\Auth\Domain\AuthRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;

class DoctrineAuthRepository extends EntityRepository implements AuthRepository
{
    /**
     * @param string $user
     * @return Auth
     * @throws PersistenceException
     */
    public function findUser(string $user): Auth
    {
        try {
            $user = $this->findOneBy(['user' => $user]);

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
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function logAccess(int $user_id): void
    {
        try {
            $this->_em->persist(new AccessLog($user_id));
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na criação de log.', $e->getMessage());
        }
    }
}
