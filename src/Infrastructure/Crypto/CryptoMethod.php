<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Crypto;

interface CryptoMethod
{
    public function hash($value);
    public function verify(string $plainPwd, string $hashedPwd);
}
