<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Command;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Category;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Tag;

class FindEventCommand
{
    /**
     * @var int|null
     */
    private $photographer;

    /**
     * @var int|null
     */
    private $publisher;

    /**
     * @var null|string
     */
    private $keyword;

    /**
     * @var array|null
     */
    private $categories;

    /**
     * @var array|null
     */
    private $tags;

    /**
     * FindEventCommand constructor.
     * @param int|null $photographer
     * @param int|null $publisher
     * @param null|string $keyword
     * @param array|null $categories
     * @param array|null $tags
     */
    public function __construct(?int $photographer, ?int $publisher, ?string $keyword, ?array $categories, ?array $tags)
    {
        $this->photographer = $photographer;
        $this->publisher = $publisher;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->keyword = $keyword;
    }

    /**
     * @return int|null
     */
    public function getPhotographer(): ?int
    {
        return $this->photographer;
    }

    /**
     * @return int|null
     */
    public function getPublisher(): ?int
    {
        return $this->publisher;
    }

    /**
     * @return array|null
     */
    public function getCategories(): ?array
    {
        $allCategories = null;
        if ($this->categories) {
            $allCategories = [];
            foreach ($this->categories as $category) {
                $allCategories[] = new Category((int) $category);
            }
        }

        return $allCategories;
    }

    /**
     * @return array|null
     */
    public function getTags(): ?array
    {
        $allTags = null;
        if ($this->tags) {
            $allTags = [];
            foreach ($this->tags as $category => $tags) {
                foreach ($tags as $tag) {
                    $allTags[$category][] = new Tag((int) $tag, null);
                }
            }
        }

        return $allTags;
    }

    /**
     * @return null|string
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }
}