<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

interface EventRepository
{
    public function find(EventSearch $search);
    public function findEventPhotos(int $id);
}