<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Domain;

interface PhotoRepository
{
    public function find(int $id): Photo;

    public function create(Photo $conf): Photo;

    public function download(Download $download): Download;

    public function like(Like $like): Like;

    public function dislike(Like $like): Like;

    public function findPhotoOwner(Photo $photo): Photographer;

    public function findPublisher(int $publisher_id): Publisher;

    public function setAsAlbumCover(string $guid): bool;

    public function deletePhoto(string $guid): Photo;
    
    public function findEventPhotos(int $event_id): ?array;
}
