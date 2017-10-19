<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Event;

use PhotoContainer\PhotoContainer\Infrastructure\Event\Event;

class DownloadApproval implements Event
{
    /**
     * @var int
     */
    private $event_id;

    /**
     * @var int
     */
    private $publisher_id;

    private $approved;

    /**
     * DownloadRequestResponse constructor.
     * @param int $event_id
     * @param int $publisher_id
     * @param bool $approved
     */
    public function __construct(int $event_id, int $publisher_id, bool $approved)
    {
        $this->approved = $approved;
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

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function getName(): string
    {
        return 'download_request_response';
    }
}