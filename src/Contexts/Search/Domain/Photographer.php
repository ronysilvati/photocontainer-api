<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Photographer
{
    private $id;
    private $name;

    const APPROVED_PROFILE = 2;

    public function __construct(?int $id, string $name = null)
    {
        $this->changeId($id);
        $this->changeName($name);
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
}
