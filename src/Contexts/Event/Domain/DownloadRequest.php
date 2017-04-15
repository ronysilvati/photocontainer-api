<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class DownloadRequest
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $event_id;

    /**
     * @var int
     */
    private $user_id;

    /**
     * @var bool
     */
    private $authorized;

    /**
     * @var bool
     */
    private $visualized;

    /**
     * @var bool
     */
    private $active;

    /**
     * DownloadRequest constructor.
     * @param int $event_id
     * @param int $user_id
     * @param bool $authorized
     * @param bool $visualized
     * @param bool $active
     */
    public function __construct(?int $int, ?int $event_id, ?int $user_id, ?bool $authorized, ?bool $visualized, ?bool $active)
    {
        $this->event_id = $event_id;
        $this->user_id = $user_id;
        $this->authorized = $authorized;
        $this->visualized = $visualized;
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function changeId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @param int $event_id
     */
    public function changeEventId(int $event_id)
    {
        $this->event_id = $event_id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function changeUserId(int $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->authorized;
    }

    /**
     * @param bool $authorized
     */
    public function changeAuthorized(bool $authorized)
    {
        $this->authorized = $authorized;
    }

    /**
     * @return bool
     */
    public function isVisualized(): bool
    {
        return $this->visualized;
    }

    /**
     * @param bool $visualized
     */
    public function changeVisualized(bool $visualized)
    {
        $this->visualized = $visualized;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function changeActive(bool $active)
    {
        $this->active = $active;
    }
}