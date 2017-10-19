<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;


use Ramsey\Uuid\Uuid;

class Photo
{
    public $file;

    private $id;
    private $event_id;
    private $physicalName;

    /**
     * Photo constructor.
     * @param int|null $id
     * @param int|null $event_id
     * @param array|null $file
     * @param null|string $physicalName
     */
    public function __construct(?int $id = null, ?int $event_id = null, ?array $file = null, ?string $physicalName = null)
    {
        $this->changeId($id);
        $this->changeEventId($event_id);
        $this->changeFile($file);

        if ($physicalName != null) {
            $this->changePhysicalName($file['name']);
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
    public function changeId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    /**
     * @param int|null $event_id
     * @throws \DomainException
     */
    public function changeEventId($event_id = null): void
    {
        if ($event_id === null) {
            throw new \DomainException('A foto deve possuir um evento.');
        }

        $this->event_id = $event_id;
    }

    /**
     * @return array|null
     */
    public function getFile(): ?array
    {
        return $this->file;
    }

    /**
     * @param array|null $file
     */
    public function changeFile($file): void
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
     * @param string $physicalName
     */
    public function setPhysicalName(string $physicalName): void
    {
        $this->physicalName = $physicalName;
    }

    /**
     * @param mixed $physicalName
     * @throws \DomainException
     */
    public function changePhysicalName(?string $physicalName): void
    {
        $path_parts = pathinfo($physicalName);

        if (! isset($path_parts['extension'])) {
            throw new \DomainException('Não foi possivel obter a extensão do arquivo.');
        }

        $extension = $path_parts['extension'];
        $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $this->event_id.$physicalName)->toString();
        $new_filename = basename($uuid) . '.' . $extension;

        $this->physicalName = $new_filename;
    }

    /**
     * @param null|string $thatFolder
     * @param bool $with_shared_path
     * @param bool $with_filename
     * @return string
     */
    public function getFilePath(?string $thatFolder, $with_shared_path = false, $with_filename = false): string
    {
        $file_path = 'events/' . $this->getEventId() . '/' . $thatFolder;

        $file_path = $file_path[-1] == '/' ? $file_path : $file_path. '/';

        if ($with_shared_path) {
            $file_path = getenv('SHARED_PATH') . '/' . $file_path;
        }
        if ($with_filename) {
            $file_path .= $this->getPhysicalName();
        }

        return $file_path;
    }

    public function getWatermarkFile(): string
    {
        return getenv('SHARED_PATH') . '/watermark.png';
    }
}
