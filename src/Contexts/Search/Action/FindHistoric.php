<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\HistoricCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

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
     * @param int $id
     * @param null|string $keyword
     * @param array|null $tags
     * @param string $type
     * @return HistoricCollectionResponse|DomainExceptionResponse
     */
    public function handle(int $id, ?string $keyword, ?array $tags, string $type)
    {
        switch ($type) {
            case 'favorites':
                $result = $this->repository->searchLikes($id, $keyword, $tags);
                break;
            case 'downloads':
                $result = $this->repository->searchDownloaded($id, $keyword, $tags);
                break;
        }

        return new HistoricCollectionResponse($result);

    }
}
