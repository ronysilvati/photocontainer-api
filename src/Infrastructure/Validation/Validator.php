<?php
namespace PhotoContainer\PhotoContainer\Infrastructure\Validation;

use Respect\Validation\Validator as v;

trait Validator
{
    public function validateEmail(string $email): bool
    {
        return v::email()->validate($email);
    }

    public function validateLength(string $string, $min, $max): bool
    {
        return v::length($min, $max)->validate($string);
    }

    public function validateUrl($url): bool
    {
        return v::url()->validate($url);
    }
}
