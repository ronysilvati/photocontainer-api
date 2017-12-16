<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Domain;

class AccessLog
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $user;

    /**
     * AccessLog constructor.
     * @param int $user
     */
    public function __construct(int $user)
    {
        $this->user = $user;
    }
}