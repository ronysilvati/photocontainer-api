<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface EventRepository
{
    public function create(Event $event);
    public function find(int $id): Event;
    public function findPhotographer(Photographer $photographer);
    public function findPublisher(Publisher $publisher);
    public function createFavorite(Favorite $favorite): Favorite;
    public function removeFavorite(Favorite $favorite): bool;
    public function findFavorite(Favorite $favorite): Favorite;
}