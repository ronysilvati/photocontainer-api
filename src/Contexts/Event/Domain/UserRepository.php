<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface UserRepository
{
    public function findPhotographer(Photographer $photographer);
    public function findPublisher(Publisher $publisher);
}