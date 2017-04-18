<?php
namespace PhotoContainer\PhotoContainer\Infrastructure\Validation;

use Respect\Validation\Validator as v;

trait Validator
{
    public function validateEmail(string $email)
    {
        return v::email()->validate($email);
    }

    public function validateLength(string $string, $min, $max)
    {
        return v::length($min, $max)->validate($string);
    }

    public function validateUrl($url)
    {
        return v::url()->validate($url);
    }
}
