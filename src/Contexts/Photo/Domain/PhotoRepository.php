<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

interface PhotoRepository
{
    public function find(int $id): Photo;

    public function create(Photo $conf): Photo;

    public function download(Download $download): Download;

    public function like(Like $like): Like;

    public function dislike(Like $like): Like;
}