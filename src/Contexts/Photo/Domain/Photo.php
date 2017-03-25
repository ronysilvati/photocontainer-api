<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

use PhotoContainer\PhotoContainer\Infrastructure\Entity;

class Photo implements Entity
{
    private $id;
    private $event_id;
    private $file;
    private $physicalName;

    /**
     * Photo constructor.
     * @param $id
     * @param $event_id
     * @param $file
     */
    public function __construct(?int $id = null, ?int $event_id = null, ?array $file = null, ?string $physicalName = null)
    {
        $this->changeId($id);
        $this->changeEventId($event_id);
        $this->changeFile($file);
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function changeId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @param int|null $event_id
     */
    public function changeEventId(?$event_id = null)
    {
        if ($this->event_id === null) {
            throw new \DomainException("A foto deve possuir um evento.");
        }

        $this->event_id = $event_id;
    }

    /**
     * @return array|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param array|null $file
     */
    public function changeFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getPhysicalName(): ?string
    {
        return $this->physicalName;
    }

    /**
     * @param mixed $physicalName
     */
    public function changePhysicalName(?string $physicalName)
    {
        $this->physicalName = $physicalName;
    }


}