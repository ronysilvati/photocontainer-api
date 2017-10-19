<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

use Respect\Validation\Exceptions\DomainException;

class Profile
{
    private $id;
    private $user_id;
    private $profile_id;
    private $active;

    const ACTIVE = 1;
    const INACTIVE = 0;

    const PHOTOGRAPHER = 2;
    const PUBLISHER = 3;

    public function __construct(int $id = null, int $user_id = null, int $profile_id = null, int $active = null)
    {
        $this->id = $id;
        $this->user_id = $user_id;

        $this->changeProfileId($profile_id);

        if ($active) {
            $this->changeActive($active);
        }
    }

    public function changeId(int $id): void
    {
        $this->id = $id;
    }

    public function changeUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function changeProfileId(int $profile_id): void
    {
        if (!in_array($profile_id, [self::PHOTOGRAPHER, self::PUBLISHER], true)) {
            throw new DomainException('O perfil selecionado é inválido.');
        }

        $this->profile_id = $profile_id;
        $this->active = $profile_id === self::PUBLISHER ? self::INACTIVE : self::ACTIVE;
    }

    public function changeActive(int $active): void
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return mixed
     */
    public function getProfileId()
    {
        return $this->profile_id;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }
}
