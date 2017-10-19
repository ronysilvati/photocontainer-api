<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class Publisher
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $profile_id;

    /**
     * @var string
     */
    private $name;

    private $email;

    const APPROVED_PROFILE = 3;

    /**
     * Publisher constructor.
     * @param int|null $id
     * @param int|null $profile_id
     * @param null|string $name
     * @param null|string $email
     */
    public function __construct(?int $id, ?int $profile_id, ?string $name, ?string $email = null)
    {
        $this->changeId($id);

        if ($profile_id !== null) {
            $this->changeProfileId($profile_id);
        }
        $this->changeName($name);

        $this->email = $email;
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
    public function changeId(?int $id): void
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
     * @throws \DomainException
     */
    public function changeProfileId(?int $profile_id): void
    {
        if (self::APPROVED_PROFILE !== $profile_id) {
            throw new \DomainException('Apenas o perfil de publisher possui permissao para executar essa operação.');
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
    public function changeName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
