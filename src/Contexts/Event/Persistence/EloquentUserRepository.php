<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\UserProfile;
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
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
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
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function findPublisher(Publisher $publisher)
    {
        try {
            $userData = $this->findUser($publisher->getId());

            if (!$userData) {
                return null;
            }

            $publisher->changeProfileId($userData['userprofile']['profile_id']);

            return $publisher;
        } catch (\Exception $e) {
            throw new PersistenceException('Erro na busca de publisher', $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws PersistenceException
     */
    private function findUser(int $id): ?array
    {
        try {
            $userModel = User::find($id);

            if (!$userModel) {
                return null;
            }

            $userModel->load('userprofile');
            return $userModel->toArray();
        } catch (\Exception $e) {
            throw new PersistenceException("O usuário não existe!", $e->getMessage());
        }
    }

    /**
     * @param int $profile
     * @return mixed
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     * @throws PersistenceException
     */
    public function findByProfile(int $profile)
    {
        try {
            $users = UserProfile::where('profile_id', $profile)
                ->with('user')
                ->get()
                ->toArray();

            $out = [];
            foreach ($users as $user) {
                $out[] = new Publisher($user['user']['id'], $profile, $user['user']['name'], $user['user']['email']);
            }

            return $out;
        } catch (\Exception $e) {
            throw new PersistenceException("Usuários não carregados.", $e->getMessage());
        }
    }
}