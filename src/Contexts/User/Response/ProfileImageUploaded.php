<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Response;

class ProfileImageUploaded implements \JsonSerializable
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getHttpStatus(): int
    {
        return 201;
    }

    public function jsonSerialize(): array
    {
        return ['profile_image' => $this->path];
    }
}