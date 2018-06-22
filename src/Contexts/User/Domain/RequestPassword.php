<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Domain;

use DateTime;
use DateInterval;

class RequestPassword
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var DateTime
     */
    private $validUntil;

    /**
     * @var int
     */
    private $user_id;

    const TTL = 'P1D';

    /**
     * RequestPassword constructor.
     * @param int|null $id
     * @param string $token
     * @param int $user_id
     * @param DateTime|null $validUntil
     */
    public function __construct(?int $id = null, string $token, int $user_id, ?DateTime $validUntil = null)
    {
        $this->id = $id;
        $this->token = $token;
        $this->user_id = $user_id;

        $this->validUntil = $validUntil;
        if (!$this->validUntil) {
            $this->createValidUntil();
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->validUntil > new DateTime('now');
    }

    /**
     * @return DateTime
     */
    public function getValidUntil(): DateTime
    {
        return $this->validUntil;
    }

    public function createValidUntil(): void
    {
        $this->validUntil = (new DateTime('now'))->add(new DateInterval(self::TTL));
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }
}