<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Command;

class FindCitiesCommand
{
    /**
     * @var string
     */
    private $state_id;

    /**
     * FindCitiesCommand constructor.
     * @param int $stateId
     */
    public function __construct(int $stateId)
    {
        $this->state_id = $stateId;
    }

    /**
     * @return int
     */
    public function getStateId(): int
    {
        return $this->state_id;
    }
}
