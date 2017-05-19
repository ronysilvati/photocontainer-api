<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\TagUpdateResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class UpdateTags
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $tags, int $id)
    {
        $tags = $this->repository->saveEventTags($tags, $id);
        return new TagUpdateResponse($tags);
    }
}
