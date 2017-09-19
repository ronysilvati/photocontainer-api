<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

interface EventRepository
{
    /**
     * @param Event $event
     * @return mixed
     */
    public function create(Event $event);

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param array $eventTags
     * @param int $id
     * @return mixed
     */
    public function saveEventTags(array $eventTags, int $id);

    /**
     * @param string $suppliers
     * @param int $id
     * @return mixed
     */
    public function saveEventSuppliers(string $suppliers, int $id);

    /**
     * @param int $id
     * @param array $data
     * @param Event $event
     * @return Event
     */
    public function update(int $id, array $data, Event $event): Event;

    /**
     * @param int $id
     * @return Event
     */
    public function find(int $id): ?Event;
}
