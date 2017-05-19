<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Response;

class PhotoResponse implements \JsonSerializable
{
    private $photos;

    public function __construct(array $photo)
    {
        $this->photos = $photo;
    }

    public function jsonSerialize()
    {
        $photo_added = [];
        foreach ($this->photos as $photo) {
            $photo_added[] = [
                'id' => $photo->getId(),
                'event_id' => $photo->getEventId(),
                'filename' => $photo->getPhysicalName(),
                'protected' => $photo->getFilePath('protected', false, false),
                'thumb' => $photo->getFilePath('thumb', false, false),
                'watermark' => $photo->getFilePath('watermark', false, false)
            ];
        }

        return $photo_added;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}
