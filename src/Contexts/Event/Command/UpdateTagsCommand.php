<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Command;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventTag;

class UpdateTagsCommand
{
    /**
     * @var array
     */
    private $tags;

    /**
     * UpdateTagsCommand constructor.
     * @param int $event_id
     * @param array $data
     */
    public function __construct(int $event_id, array $data)
    {
        $this->tags = [];
        foreach ($data['tags'] as $tag) {
            $this->tags[] = new EventTag($event_id, $tag);
        }
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}