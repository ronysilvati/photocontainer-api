<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\FavoriteRemovedResponse;
use PhotoContainer\PhotoContainer\Contexts\User\Response\DomainExceptionResponse;

class DeleteFavorite
{
    protected $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Favorite $favorite)
    {
        try {
            $favorite = $this->repository->removeFavorite($favorite);
            return new FavoriteRemovedResponse($favorite);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
