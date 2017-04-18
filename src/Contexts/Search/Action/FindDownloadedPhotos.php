<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Action;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Response\DownloadedCollectionResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class FindDownloadedPhotos
{
    protected $repository;

    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id, ?string $keyword, ?array $tags)
    {
        try {
            $result = $this->repository->searchDownloaded($id, $keyword, $tags);
            return new DownloadedCollectionResponse($result);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }
}
