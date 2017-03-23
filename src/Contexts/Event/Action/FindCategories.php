<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\CategoryRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\CategoryCollectionResponse;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\DomainExceptionResponse;

class FindCategories
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle()
    {
        try {
            $result = $this->repository->findAll();
            return new CategoryCollectionResponse($result);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}