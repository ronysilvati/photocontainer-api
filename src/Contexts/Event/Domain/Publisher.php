<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

use PhotoContainer\PhotoContainer\Infrastructure\Entity;

class Publisher implements Entity
{
    private $id;
    private $profile_id;
    private $name;

    const APPROVED_PROFILE = 3;

    public function __construct(?int $id, ?int $profile_id, ?string $name)
    {
        $this->changeId($id);

        if ($profile_id !== null) {
            $this->changeProfileId($profile_id);
        }
        $this->changeName($name);
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function changeId(?int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProfileId(): ?int
    {
        return $this->profile_id;
    }

    /**
     * @param mixed $profile_id
     */
    public function changeProfileId(?int $profile_id)
    {
        if (self::APPROVED_PROFILE !== $profile_id) {
            throw new \DomainException("Apenas o perfil de publisher possui permissao para executar essa operação.");
        }

        $this->profile_id = $profile_id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function changeName(?string $name)
    {
        $this->name = $name;
    }
}
