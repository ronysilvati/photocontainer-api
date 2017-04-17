<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Response;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Like;

class DislikeResponse implements \JsonSerializable
{
    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function jsonSerialize()
    {
        return [
            'photo_id' => $this->like->getPhotoId(),
            'user_id' => $this->like->getUserId(),
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
