<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Domain;

class Event
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $user_id;

    /**
     * Event constructor.
     * @param string $name
     * @param string $user_id
     */
    public function __construct(string $name, string $user_id)
    {
        $this->name = $name;
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }
}
