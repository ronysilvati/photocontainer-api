<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Command;

class DownloadSelectedCommand
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $publisherId;

    /**
     * @var string
     */
    private $ids;

    /**
     * DownloadSelectedCommand constructor.
     * @param string $type
     * @param int $publisherId
     * @param string $ids
     */
    public function __construct(string $type, int $publisherId, string $ids)
    {
        $this->type = $type;
        $this->publisherId = $publisherId;
        $this->ids = $ids;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPublisherId(): int
    {
        return $this->publisherId;
    }

    /**
     * @return string
     */
    public function getIds(): string
    {
        return $this->ids;
    }
}