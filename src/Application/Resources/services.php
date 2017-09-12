<?php
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\FSPhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Persistence\FilesystemPhotoRepository;

$services = [];

$services[FSPhotoRepository::class] = function ($c) {
    return new FilesystemPhotoRepository();
};

$services['PhotoContainer\PhotoContainer\Contexts\*\Domain\*Repository'] = DI\object(
    'PhotoContainer\PhotoContainer\Contexts\*\Persistence\Eloquent*Repository'
);

return $services;