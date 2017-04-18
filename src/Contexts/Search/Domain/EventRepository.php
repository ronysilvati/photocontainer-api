<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

interface EventRepository
{
    public function find(EventSearch $search);
    public function findWaitingRequests(int $photographer_id): ?array;
    public function findEventPhotosPhotographer(int $id): Event;
    public function findEventPhotosPublisher(int $id, int $user_id): Event;
}