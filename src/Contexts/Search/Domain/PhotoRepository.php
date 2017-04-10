<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

interface PhotoRepository
{
    public function searchDownloaded(int $user_id, ?string $keyword, ?array $tags);
}