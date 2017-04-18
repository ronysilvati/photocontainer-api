<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Approval;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Category;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\EventSearch;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Event;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\DownloadRequest;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event as EventModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventFavorite;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearch as EventSearchModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearchApproval;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearchPublisher;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\PhotoFavorite;

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

            $publisher = $search->getPublisher();

            $modelSearch = $publisher ? EventSearchPublisher::where($where) : EventSearchModel::where($where);

            $eventSearch = $modelSearch
                ->groupBy('id', 'category_id', 'category')
                ->get([
                    'id', 'user_id', 'name', 'title', 'eventdate', 'category_id', 'category', 'photos', 'likes',
                ]);

            $out = ['total' => $eventSearch->count()];

            $eventSearch = $eventSearch->forPage($search->getPage(), 1000);

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
            throw $e;
        }
    }

    public function findEventPhotosPublisher(int $id, int $user_id): Event
    {
        try {
            $eventModel = EventModel::find($id);
            $eventData = $eventModel->load('EventCategory', 'User', 'Photo')->toArray();

            $categories = $eventModel->EventCategory->load('Category')->toArray();

            $photos = [];
            foreach ($eventData['photo'] as $photo) {
                $liked = PhotoFavorite::where(['user_id' => $user_id, 'photo_id' => $photo['id']])->count();

                $photos[] = [
                    'id' => $photo['id'],
                    "thumb" => "/user/themes/photo-container-site/_temp/photos/1.jpg",
                    "filename" => $photo['filename'],
                    'context' => 'gallery_photos_publisher',
                    'liked' => $liked,
                ];
            }

            $event = new Event(
                $eventData['id'],
                $eventData['title'],
                $eventData['user']['name'],
                $categories[0]['category']['description'],
                $photos
            );

            $event->changeApprovedForPublisher(true);
            if ($eventData['approval_general'] == false &&
                ($eventData['approval_photographer'] == false || $eventData['approval_bride'] == false)) {
                $dlReq = DownloadRequest::where('user_id', $user_id)
                    ->where('event_id', $id)
                    ->where('authorized', 1)
                    ->first();

                $event->changeApprovedForPublisher($dlReq != null);
            }

            return $event;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function findEventPhotosPhotographer(int $id): Event
    {
        try {
            $eventModel = EventModel::find($id);
            $eventData = $eventModel->load('EventCategory', 'User', 'Photo')->toArray();

            $categories = $eventModel->EventCategory->load('Category')->toArray();

            $photos = [];
            foreach ($eventData['photo'] as $photo) {
                $photos[] = [
                    'id' => $photo['id'],
                    "thumb" => "/events/{$id}/thumb/".$photo['filename'],
                    "filename" => $photo['filename'],
                    'context' => 'gallery_photos_photographer',
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

    public function findWaitingRequests(int $photographer_id): ?array
    {
        try {
            $list = EventSearchApproval::where('photographer_id', $photographer_id)->get();

            return $list->map(function ($item, $key) {
                if ($item->visualized == false) {
                    $dlRequest = DownloadRequest::find($item->id);
                    $dlRequest->visualized = 1;
                    $dlRequest->save();
                }

                return new Approval($item->photographer_id, $item->publisher_id, $item->created_at, $item->title);
            })->toArray();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
            throw new PersistenceException("Erro na listagem das aprovações pendentes.");
        }
    }
}
