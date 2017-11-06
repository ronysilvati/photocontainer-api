<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class Photographer
{
    private $id;
    private $profile_id;
    private $name;
    private $site;

    const APPROVED_PROFILE = 2;

    public function __construct(?int $id, int $profile_id = null, string $name = null, string $site = '')
    {
        $this->changeId($id);

        if ($profile_id !== null) {
            $this->changeProfileId($profile_id);
        }
        $this->changeName($name);
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function changeId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProfileId()
    {
        return $this->profile_id;
    }

    /**
     * @param mixed $profile_id
     * @throws \DomainException
     */
    public function changeProfileId($profile_id): void
    {
        if (self::APPROVED_PROFILE !== $profile_id) {
            throw new \DomainException('Apenas o perfil de fotógrafo possui permissao para executar essa operação.');
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
    public function changeName(?string $name = null): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }
}
