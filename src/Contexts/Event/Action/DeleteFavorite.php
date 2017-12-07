<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Command\DeleteFavoriteCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\FavoriteRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\FavoriteRemovedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DeleteFavorite
{
    protected $repository;

    public function __construct(FavoriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DeleteFavoriteCommand $command)
    {
        try {
            $favorite = $command->getFavorite();

            $favorite = $this->repository->removeFavorite($favorite);
            return new FavoriteRemovedResponse($favorite);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
