<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Category
{
    private $id;
    private $description;

    /**
     * Category constructor.
     * @param $id
     * @param $description
     */
    public function __construct(int $id, string $description = null)
    {
        $this->changeId($id);
        $this->changeDescription($description);
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
    public function changeId(int $id): void
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
    public function changeDescription(string $description = null): void
    {
        $this->description = $description;
    }
}
