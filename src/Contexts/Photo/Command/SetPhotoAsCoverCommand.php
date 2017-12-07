<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Command;

class SetPhotoAsCoverCommand
{
    /**
     * @var string
     */
    private $guid;

    /**
     * SetPhotoAsCoverCommand constructor.
     * @param string $guid
     */
    public function __construct(string $guid)
    {
        $this->guid = $guid;
    }

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }
}