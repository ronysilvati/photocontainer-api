<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Category;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventSearch;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Tag;
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
     * @param array $args
     * @return array|false|mixed|EventCollectionResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException
     */
    public function handle(array $args)
    {
        $argMD5 = md5(serialize($args));
        $result = $this->cacheHelper->getByNamespace('find_event', $argMD5);
        if ($result) {
            return new EventCollectionResponse($result);
        }

        $keyword = $args['keyword'] ?? null;
        $photographer = new Photographer((int) $args['photographer'] ?? $args['photographer']);

        $allCategories = null;
        if (!empty($args['categories'])) {
            $allCategories = [];
            foreach ($args['categories'] as $category) {
                $allCategories[] = new Category((int) $category);
            }
        }

        $allTags = null;
        if (!empty($args['tags'])) {
            $allTags = [];
            foreach ($args['tags'] as $category => $tags) {
                foreach ($tags as $tag) {
                    $allTags[$category][] = new Tag((int) $tag, null);
                }
            }
        }

        $search = new EventSearch(null, $photographer, $keyword, $allCategories, $allTags, 1);

        if (!empty($args['publisher'])) {
            $search->changePublisher(new Publisher((int) $args['publisher'] ?? $args['publisher']));
        }

        $result = $this->repository->find($search);

        $this->cacheHelper->saveByNamespace('find_event', $argMD5, $result);

        return new EventCollectionResponse($result);
    }
}
