<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Response;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;

class DeletedPhotoResponse implements \JsonSerializable
{
    private $photo;

    public function __construct(Photo $photo)
    {
        $this->photo = $photo;
    }

    public function jsonSerialize()
    {
        return [
            'message' => 'Removido com sucesso.',
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}