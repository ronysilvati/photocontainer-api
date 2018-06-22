<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\TagRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\TagCollectionResponse;


class FindTags
{
    protected $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(): \PhotoContainer\PhotoContainer\Contexts\Search\Response\TagCollectionResponse
    {
        $result = $this->repository->findAll();
        return new TagCollectionResponse($result);
    }
}
