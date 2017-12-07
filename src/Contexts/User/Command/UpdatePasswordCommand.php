<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Command;

class UpdatePasswordCommand
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $pwd;

    /**
     * UpdatePasswordCommand constructor.
     * @param string $token
     * @param string $pwd
     */
    public function __construct($token, $pwd)
    {
        $this->token = $token;
        $this->pwd = $pwd;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getPwd(): string
    {
        return $this->pwd;
    }
}