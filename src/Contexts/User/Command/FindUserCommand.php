<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Command;

class FindUserCommand
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $email;

    /**
     * FindUserCommand constructor.
     * @param int|null $id
     * @param null|string $email
     */
    public function __construct(?int $id, ?string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}