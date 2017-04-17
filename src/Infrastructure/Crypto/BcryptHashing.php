<?php
/**
 * Created by PhpStorm.
 * User: marte
 * Date: 19/03/2017
 * Time: 10:30
 */

namespace PhotoContainer\PhotoContainer\Infrastructure\Crypto;

class BcryptHashing implements CryptoMethod
{
    public function hash($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    public function verify(string $plainPwd, string $hashedPwd)
    {
        return password_verify($plainPwd, $hashedPwd);
    }
}
