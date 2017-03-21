<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

use PhotoContainer\PhotoContainer\Infrastructure\Entity;

interface UserRepository
{
    public function createUser(Entity $user, string $encryptedPwd);
    public function updateUser(Entity $user, string $encryptedPwd);
    public function findUser(int $id);
}