<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

interface UserRepository
{
    public function createUser(User $user, ?string $encryptedPwd);
    public function updateUser(User $user);
    public function findUser(?int $id = null, ?string $email = null): ?User;
    public function isUserUnique(string $email): bool;
    public function isUserSlotsAvailable(int $maxSlots): bool;
    public function findPwdRequest(User $user): ?RequestPassword;
    public function createPwdRequest(RequestPassword $requestPassword): RequestPassword;
    public function removePwdRequest(RequestPassword $requestPassword): void;
    public function getValidToken(string $token): ?RequestPassword;
}
