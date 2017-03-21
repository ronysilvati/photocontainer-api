<?php
/**
 * Created by PhpStorm.
 * User: marte
 * Date: 18/03/2017
 * Time: 18:32
 */

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Domain;

use PhotoContainer\PhotoContainer\Infrastructure\Entity;

class Auth implements Entity
{
    private $user;
    private $password;

    public function __construct(string $user, string $pwd)
    {
        $this->user = $user;
        $this->password = $pwd;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }
}