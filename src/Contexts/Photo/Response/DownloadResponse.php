<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Response;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;

class DownloadResponse
{
    private $download;

    public function __construct(Download $download)
    {
        $this->download = $download;
    }

    public function getDownload(): Download
    {
        return $this->download;
    }

    public function getFileToStream()
    {
        return fopen($this->download->getPhoto()->getFilePath('protected', true, true), 'rb');
    }
}
