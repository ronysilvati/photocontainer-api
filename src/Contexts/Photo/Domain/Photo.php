<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

use PhotoContainer\PhotoContainer\Infrastructure\Entity;
use Ramsey\Uuid\Uuid;

class Photo implements Entity
{
    public $file;

    private $id;
    private $event_id;
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
    public function changeEventId($event_id = null)
    {
        if ($event_id === null) {
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
     * @return mixed
     */
    public function setPhysicalName(string $physicalName)
    {
        $this->physicalName = $physicalName;
    }

    /**
     * @param mixed $physicalName
     */
    public function changePhysicalName(?string $physicalName)
    {
        $path_parts = pathinfo($physicalName);

        $extension = $path_parts['extension'];
        $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $this->event_id.$physicalName)->toString();
        $new_filename = basename($uuid) . "." . $extension;

        $this->physicalName = $new_filename;
    }

    /**
     * @param null|string $thatFolder
     * @param bool $with_shared_path
     * @param bool $with_filename
     * @return string
     */
    public function getFilePath(?string $thatFolder, $with_shared_path = false, $with_filename = false) {

        $file_path = 'events/' . $this->getEventId() . '/' . $thatFolder;

        $file_path = $file_path[-1] == "/" ? $file_path : $file_path."/";

        if ($with_shared_path) $file_path = $_ENV['SHARED_PATH'] . '/' . $file_path;
        if ($with_filename) $file_path = $file_path . $this->getPhysicalName();

        return $file_path;
    }

    public function getWatermarkFile() {
        return $_ENV['SHARED_PATH'] . '/watermark.png';
    }

}