<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\CategoryRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\CategoryCollectionResponse;

class FindCategories
{
    /**
     * @var CategoryRepository
     */
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(): \PhotoContainer\PhotoContainer\Contexts\Search\Response\CategoryCollectionResponse
    {
        $result = $this->repository->findAll();
        return new CategoryCollectionResponse($result);
    }
}
