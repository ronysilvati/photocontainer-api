<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Command;

class WaitingForApprovalCommand
{
    /**
     * @var int
     */
    private $photographerId;

    /**
     * WaitingForApprovalCommand constructor.
     * @param int $photographerId
     */
    public function __construct(int $photographerId)
    {
        $this->photographerId = $photographerId;
    }

    /**
     * @return int
     */
    public function getPhotographerId(): int
    {
        return $this->photographerId;
    }
}