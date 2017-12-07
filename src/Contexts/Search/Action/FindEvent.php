<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Command\FindEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventSearch;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Search\Persistence\DbalEventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\EventCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;

class FindEvent
{
    /**
     * @var DbalEventRepository
     */
    protected $repository;

    /**
     * @var CacheHelper
     */
    private $cacheHelper;

    public function __construct(DbalEventRepository $repository, CacheHelper $cacheHelper)
    {
        $this->repository = $repository;
        $this->cacheHelper = $cacheHelper;
    }

    /**
     * @param FindEventCommand $command
     * @return EventCollectionResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function handle(FindEventCommand $command): \PhotoContainer\PhotoContainer\Contexts\Search\Response\EventCollectionResponse
    {
        $argMD5 = md5(serialize($command));
        $result = $this->cacheHelper->getByNamespace('find_event', $argMD5);
        if ($result) {
            return new EventCollectionResponse($result);
        }

        $photographer = new Photographer($command->getPhotographer());
        $allCategories = $command->getCategories();
        $allTags = $command->getTags();

        $search = new EventSearch(null, $photographer, $command->getKeyword(), $allCategories, $allTags, 1);

        if ($command->getPublisher()) {
            $search->changePublisher(new Publisher($command->getPublisher()));
        }

        $result = $this->repository->find($search);

        $this->cacheHelper->saveByNamespace('find_event', $argMD5, $result);

        return new EventCollectionResponse($result);
    }
}
