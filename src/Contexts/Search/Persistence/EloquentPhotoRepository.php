<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Historic;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearchPublisherDownload;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearchPublisherFavorite;


class EloquentPhotoRepository implements PhotoRepository
{
    public function searchDownloaded(int $user_id, ?string $keyword, ?array $tags)
    {
        $where = $this->buildWhere($user_id, $keyword, $tags);

        $data = EventSearchPublisherDownload::where($where)
            ->groupBy('id')
            ->orderBy('id', 'desc')
            ->get();

        return $data->map(function ($item) {
            return new Historic($item->photo_id, $item->user_id, $item->event_id, $item->filename, $item->favorite, true);
        })->toArray();
    }

    public function searchLikes(int $user_id, ?string $keyword, ?array $tags)
    {
        $where = $this->buildWhere($user_id, $keyword, $tags);

        $data = EventSearchPublisherFavorite::where($where)
            ->groupBy('id')
            ->orderBy('created_at', 'DESC')
            ->get();

        return $data->map(function ($item) {
            return new Historic($item->photo_id, $item->user_id, $item->event_id, $item->filename, $item->favorite, $item->authorized);
        })->toArray();
    }

    private function buildWhere(int $user_id, ?string $keyword, ?array $tags): array
    {
        $where = [
            ['user_id', $user_id]
        ];

        if ($keyword) {
            $where[] = ['title', 'like', '%' .$keyword. '%'];
            $where[] = ['name', 'like', '%' .$keyword. '%'];
        }

        if ($tags) {
            $tagsSearch = [];
            foreach ($tags as $tag) {
                $tagsSearch[] = $tag->getId();
            }

            $where[] = ['tag_id', $tagsSearch];
        }

        return $where;
    }
}
