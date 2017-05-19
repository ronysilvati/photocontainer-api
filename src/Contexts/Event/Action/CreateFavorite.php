<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\FavoriteRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\FavoriteCreatedResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class CreateFavorite
{
    /**
     * @var FavoriteRepository
     */
    protected $repository;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * CreateFavorite constructor.
     * @param FavoriteRepository $repository
     * @param UserRepository $userRepo
     */
    public function __construct(FavoriteRepository $repository, UserRepository $userRepo)
    {
        $this->repository = $repository;
        $this->userRepo = $userRepo;
    }

    /**
     * @param Favorite $favorite
     * @return FavoriteCreatedResponse|DomainExceptionResponse
     */
    public function handle(Favorite $favorite)
    {
        try {
            $favorite->changePublisher($this->userRepo->findPublisher($favorite->getPublisher()));
            $favorite = $this->repository->createFavorite($favorite);

            return new FavoriteCreatedResponse($favorite);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
