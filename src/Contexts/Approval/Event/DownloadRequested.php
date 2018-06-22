<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Event;

use PhotoContainer\PhotoContainer\Infrastructure\Event\Event;

class DownloadRequested implements Event
{
    /**
     * @var int
     */
    private $event_id;

    /**
     * @var int
     */
    private $publisher_id;

    /**
     * DownloadRequested constructor.
     * @param int $event_id
     * @param int $publisher_id
     */
    public function __construct(int $event_id, int $publisher_id)
    {
        $this->event_id = $event_id;
        $this->publisher_id = $publisher_id;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @return int
     */
    public function getPublisherId(): int
    {
        return $this->publisher_id;
    }
    
    public function getName(): string
    {
        return 'download_requested';
    }
}