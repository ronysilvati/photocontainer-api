<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Response;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\SelectedPhotos;

class DownloadSelectedResponse
{
    private $selectedPhotos;

    public function __construct(SelectedPhotos $selectedPhotos)
    {
        $this->selectedPhotos = $selectedPhotos;
    }

    /**
     * @return SelectedPhotos
     */
    public function getSelectedPhotos(): SelectedPhotos
    {
        return $this->selectedPhotos;
    }

    public function getFileToStream()
    {
        return fopen(
            $this->selectedPhotos->getZip(),
            'rb'
        );
    }
}
