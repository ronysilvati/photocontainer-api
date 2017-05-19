<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use League\Flysystem\Exception;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\CategoryRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\CategoryCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class FindCategories
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle()
    {
        $result = $this->repository->findAll();
        return new CategoryCollectionResponse($result);
    }
}
