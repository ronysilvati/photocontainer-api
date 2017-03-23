<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class Tag
{
    private $id;
    private $description;

    public function __construct(int $id, string $description)
    {
        $this->changeDescription($description);
        $this->changeId($id);
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
    public function changeId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function changeDescription($description)
    {
        $this->description = $description;
    }
}