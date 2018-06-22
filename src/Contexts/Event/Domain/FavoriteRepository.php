<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface FavoriteRepository
{
    public function createFavorite(Favorite $favorite): Favorite;
    public function removeFavorite(Favorite $favorite): Favorite;
    public function findFavorite(Favorite $favorite): ?Favorite;
}