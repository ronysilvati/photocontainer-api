<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Command\FindHistoricCommand;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\HistoricCollectionResponse;


class FindHistoric
{
    /**
     * @var PhotoRepository
     */
    protected $repository;

    /**
     * FindHistoric constructor.
     * @param PhotoRepository $repository
     */
    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindHistoricCommand $command
     * @return HistoricCollectionResponse
     */
    public function handle(FindHistoricCommand $command): HistoricCollectionResponse
    {
        switch ($command->getType()) {
            case 'favorites':
                $result = $this->repository
                    ->searchLikes($command->getPublisherId(), $command->getKeyword(), $command->getTags());
                break;
            case 'downloads':
                $result = $this->repository
                    ->searchDownloaded($command->getPublisherId(), $command->getKeyword(), $command->getTags());
                break;
        }

        return new HistoricCollectionResponse($result);

    }
}
