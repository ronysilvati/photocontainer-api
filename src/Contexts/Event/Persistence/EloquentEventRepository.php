<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Search;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventCategory;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSearch;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event as EventModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventTag;

class EloquentEventRepository implements EventRepository
{
    public function create(Event $event)
    {
        try {
            $eventModel = new EventModel();
            $eventModel->groom = $event->getGroom();
            $eventModel->bride = $event->getBride();
            $eventModel->eventdate = $event->getEventDate();
            $eventModel->title = $event->getTitle();
            $eventModel->description = $event->getDescription();
            $eventModel->terms = $event->getTerms();
            $eventModel->approval_general = $event->getApprovalGeneral();
            $eventModel->approval_photographer = $event->getApprovalPhotographer();
            $eventModel->approval_bride = $event->getApprovalBride();
            $eventModel->user_id = $event->getPhotographer()->getId();
            $eventModel->save();

            $event->changeId($eventModel->id);

            $categories = $event->getCategories();
            foreach ($categories as $cat) {
                EventCategory::create(['event_id' => $event->getId(), 'category_id' => $cat->getCategoryId()]);
            }

            $tags = $event->getTags();
            foreach ($tags as $tag) {
                EventTag::create(['event_id' => $event->getId(), 'tag_id' => $tag->getTagId()]);
            }

            return $event;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
            throw new PersistenceException($e->getMessage());
        }
    }

    public function findPhotographer(Photographer $photographer)
    {
        try {
            $userModel = User::find($photographer->getId());
            $userModel->load('userprofile');
            $userData = $userModel->toArray();

            $photographer->changeProfileId($userData['userprofile']['profile_id']);

            return $photographer;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function search(Search $search)
    {
        try {
            $where = [];

            if ($search->getTitle()) {
                $where[] = ['title', 'like', "%".$search->getTitle()."%"];
            }

            if ($search->getPhotographer()) {
                $where[] = ['user_id', $search->getPhotographer()];
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

            $eventSearch = EventSearch::where($where)->groupBy('id')->get(['id', 'user_id', 'name', 'title', 'eventdate']);

            return $eventSearch->map(function ($item, $key) {
                $search = new Search($item->id, $item->name, $item->title, null, null);
                $search->changeEventdate($item->eventdate);

                return $search;
            })->toArray();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }


}