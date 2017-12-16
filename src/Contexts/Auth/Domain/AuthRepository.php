<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Domain;

interface AuthRepository
{
    /**
     * @param string $user
     * @return Auth
     */
    public function findUser(string $user): Auth;

    /**
     * @param int $user_id
     * @return mixed
     */
    public function logAccess(int $user_id);
}
