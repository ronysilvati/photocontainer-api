<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface EventRepository
{
    public function create(Event $event);
    public function delete(int $id);
    public function saveEventTags(array $eventTags, int $id);
    public function saveEventSuppliers(string $suppliers, int $id);
    public function update(int $id, array $data, Event $event): Event;
    public function find(int $id): Event;
}
