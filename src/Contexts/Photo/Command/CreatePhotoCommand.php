<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Command;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;
use Psr\Http\Message\UploadedFileInterface;

class CreatePhotoCommand
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * @var array
     */
    private $photos;

    /**
     * CreatePhotoCommand constructor.
     * @param array $data
     * @param array $uploadedFiles
     */
    public function __construct(array $data, array $uploadedFiles)
    {
        $this->eventId = (int) $data['event_id'];

        $this->photos = [];
        foreach ($uploadedFiles as $files) {
            /** @var UploadedFileInterface $file */
            foreach ($files as $file) {
                if ($file->getError() !== UPLOAD_ERR_OK) {
                    throw new \RuntimeException('Erro no envio do arquivo.');
                }

                $filedata = [
                    'error' => $file->getError(),
                    'name' => $file->getClientFilename(),
                    'size' => $file->getSize(),
                    'tmp_name' => $file->file,
                    'type' => $file->getClientMediaType(),
                ];

                $this->photos[] = new Photo(null, $this->eventId, $filedata, $file->getClientFilename());
            }
        }
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }
}