<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Command;

class SearchResourceCommand
{
    /**
     * @var string
     */
    private $resource;

    /**
     * @var array
     */
    private $queryParams;

    /**
     * SearchResourceCommand constructor.
     * @param string $resource
     * @param array $queryParams
     */
    public function __construct(string $resource, array $queryParams)
    {
        $this->resource = $resource;
        $this->queryParams = $queryParams;
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }
}