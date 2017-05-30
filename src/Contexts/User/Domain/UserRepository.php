<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

interface UserRepository
{
    public function createUser(User $user, ?string $encryptedPwd);
    public function updateUser(User $user);
    public function findUser(?int $id = null, ?string $email = null);
    public function isUserUnique(string $email): bool;
    public function isUserSlotsAvailable(int $maxSlots): bool;
}
