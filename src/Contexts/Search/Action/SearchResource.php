<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Command\SearchResourceCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\SearchResourceCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\SearchEngine\SearchEngine;

class SearchResource
{
    /**
     * @var SearchEngine
     */
    private $searchEngine;

    /**
     * SearchResource constructor.
     * @param $searchEngine
     */
    public function __construct(SearchEngine $searchEngine)
    {
        $this->searchEngine = $searchEngine;
    }

    /**
     * @param SearchResourceCommand $command
     * @return SearchResourceCollectionResponse
     */
    public function handle(SearchResourceCommand $command): SearchResourceCollectionResponse
    {
        $result = $this->searchEngine->addResource($command->getResource())
            ->query($command->getQueryParams());

        return new SearchResourceCollectionResponse($result);
    }
}