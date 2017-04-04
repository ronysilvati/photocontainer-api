<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventFoundResponse;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\EventUpdateResponse;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\TagUpdateResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\DomainExceptionResponse;
use Symfony\Component\Config\Definition\Exception\Exception;

class UpdateTags
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $tags, int $id)
    {
        try {
            $tags = $this->repository->saveEventTags($tags, $id);
            return new TagUpdateResponse($tags);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}