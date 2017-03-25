<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\TagRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\DomainExceptionResponse;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\TagCollectionResponse;

class FindTags
{
    protected $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle()
    {
        try {
            $result = $this->repository->findAll();
            return new TagCollectionResponse($result);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}