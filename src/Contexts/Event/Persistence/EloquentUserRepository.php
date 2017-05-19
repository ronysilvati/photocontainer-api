<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;

class EloquentUserRepository implements UserRepository
{
    /**
     * @var EloquentDatabaseProvider
     */
    private $conn;

    public function __construct(EloquentDatabaseProvider $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param Photographer $photographer
     * @return Photographer
     * @throws PersistenceException
     */
    public function findPhotographer(Photographer $photographer)
    {
        try {
            $userData = $this->findUser($photographer->getId());
            $photographer->changeProfileId($userData['userprofile']['profile_id']);

            return $photographer;
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na pesquisa do fotógrafo.', $e->getMessage());
        }
    }

    /**
     * @param Publisher $publisher
     * @return Publisher
     * @throws PersistenceException
     */
    public function findPublisher(Publisher $publisher)
    {
        try {
            $userData = $this->findUser($publisher->getId());
            $publisher->changeProfileId($userData['userprofile']['profile_id']);

            return $publisher;
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na busca de publisher', $e->getMessage());
        }
    }

    private function findUser(int $id)
    {
        try {
            $userModel = User::find($id);
            $userModel->load('userprofile');
            return $userModel->toArray();
        } catch (\Exception $e) {
            throw new PersistenceException("O usuário não existe!", $e->getMessage());
        }
    }
}