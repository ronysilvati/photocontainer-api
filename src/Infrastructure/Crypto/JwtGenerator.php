<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Crypto;

use Firebase\JWT\JWT;

class JwtGenerator implements CryptoMethod
{
    public $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function hash($value)
    {
        return JWT::encode($value, $this->key);
    }

    public function verify(string $plainPwd, string $hashedPwd)
    {
        // TODO: Implement verify() method.
    }

}