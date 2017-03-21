<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface EventRepository
{
    public function create(Event $event);
    public function findPhotographer(Photographer $photographer);
    public function search(Search $search);
}