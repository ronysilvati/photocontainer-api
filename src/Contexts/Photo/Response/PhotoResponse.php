<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Response;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Photo;

class PhotoResponse implements \JsonSerializable
{
    private $photo;

    public function __construct(array $photo)
    {
        $this->photo = $photo;
    }

    function jsonSerialize()
    {
        // TODO: retorno mais certinho
        return $this->photo;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 200;
    }
}