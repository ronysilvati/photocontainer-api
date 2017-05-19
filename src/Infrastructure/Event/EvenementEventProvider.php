<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Event;

use Evenement\EventEmitter;

class EvenementEventProvider extends EventEmitter implements EventProvider
{
    /**
     * @var array
     */
    private $allEvents;

    /**
     * @param array $contextEvents
     */
    public function addContextEvents(?array $contextEvents): void
    {
        if ($this->allEvents === null) {
            $this->allEvents = [];
        }

        if ($contextEvents === null) {
            return;
        }

        $this->allEvents[] = $contextEvents;
    }

    public function releaseAllEvents(): void
    {
        if (!$this->allEvents) {
            return;
        }

        foreach ($this->allEvents as $contextEvents) {
            foreach ($contextEvents as $eventName => $data) {
                $this->emit($eventName, $data);
            }
        }
    }
}