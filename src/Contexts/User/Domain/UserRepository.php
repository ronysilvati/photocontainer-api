<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

interface UserRepository
{
    public function createUser(User $user, ?string $encryptedPwd);
    public function updateUser(User $user, ?string $encryptedPwd);
    public function findUser(int $id, string $email = null);
}