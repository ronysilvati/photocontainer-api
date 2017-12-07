<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Command;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Tag;

class FindHistoricCommand
{
    /**
     * @var int
     */
    private $publisherId;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var string
     */
    private $type;

    /**
     * FindHistoricCommand constructor.
     * @param int $publisherId
     * @param string $keyword
     * @param array $tags
     * @param string $type
     */
    public function __construct(int $publisherId, ?string $keyword, ?array $tags, string $type)
    {
        $this->publisherId = $publisherId;
        $this->keyword = $keyword;
        $this->tags = $tags;
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getPublisherId(): int
    {
        return $this->publisherId;
    }

    /**
     * @return string
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * @return array
     */
    public function getTags(): ?array
    {
        $allTags = null;
        if ($this->tags) {
            $allTags = [];
            foreach ($this->tags as $tag) {
                if ($tag !== '') {
                    $allTags[] = new Tag((int) $tag, null);
                }
            }
        }

        return $allTags;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}