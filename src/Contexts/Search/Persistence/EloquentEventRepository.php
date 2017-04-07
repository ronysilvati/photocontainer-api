<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Category;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventSearch;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Event;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event as EventModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventFavorite;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearch as EventSearchModel;

class EloquentEventRepository implements EventRepository
{
   public function find(EventSearch $search)
    {
        try {
            $where = [];

            if ($search->getTitle()) {
                $where[] = ['title', 'like', "%".$search->getTitle()."%"];
            }

            if ($search->getPhotographer()->getId()) {
                $where[] = ['user_id', $search->getPhotographer()->getId()];
            }

            $allCategories = $search->getCategories();
            if ($allCategories) {
                $categories = [];
                foreach ($allCategories as $category) {
                    $categories[] = $category->getId();
                }

                $where[] = ['category_id', $categories];
            }

            $allTags = $search->getTags();
            if ($allTags) {
                $tags = [];
                foreach ($allTags as $tag) {
                    $tags[] = $tag->getId();
                }

                $where[] = ['tag_id', $tags];
            }

            $eventSearch = EventSearchModel::where($where)
                ->groupBy('id', 'category_id', 'category')
                ->get(['id', 'user_id', 'name', 'title', 'eventdate', 'category_id', 'category', 'photos', 'likes']);

            $out = ['total' => $eventSearch->count()];

            $eventSearch = $eventSearch->forPage($search->getPage(), 15);

            $publisher = $search->getPublisher();
            $out['result'] = $eventSearch->map(function ($item, $key) use ($publisher) {
                $category = new Category($item->category_id, $item->category);
                $photographer = new Photographer($item->user_id, $item->name);

                $search = new EventSearch($item->id, $photographer, $item->title, [$category], null);
                $search->changeEventdate($item->eventdate);
                $search->changePhotos($item->photos);
                $search->changeLikes($item->likes);

                if ($publisher) {
                    $search->changePublisher($publisher);

                    if ($item->likes > 0) {
                        $total = EventFavorite::where('event_id', $item->id)
                            ->where('user_id', $publisher->getId())
                            ->count();

                        $search->changePublisherLike($total > 0);
                    }
                }
                return $search;
            })->toArray();

            return $out;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }

    public function findEventPhotos(int $id)
    {
        try {
            $eventModel = EventModel::find($id);
            $eventData = $eventModel->load('EventCategory', 'User', 'Photo')->toArray();

            $categories = $eventModel->EventCategory->load('Category')->toArray();

            $photos = [];
            foreach ($eventData['photo'] as $photo) {
                $photos[] = [
                    'id' => $photo['id'],
                    "thumb" => "http://192.168.99.100/user/themes/photo-container-site/_temp/photos/1.jpg",
//                    'thumb' => $photo['uuid'],
                    'download' => $photo['uuid'],
                    'context' => 'gallery_photos_publisher',
                ];
            }

            return new Event(
                $eventData['id'],
                $eventData['title'],
                $eventData['user']['name'],
                $categories[0]['category']['description'],
                $photos
            );
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }
}