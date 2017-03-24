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
        $eventSearch = new EventSearch();

        if ($search->getTitle()) {
            $eventSearch = $eventSearch->where('title', 'like', "%".$search->getTitle()."%");
        }

        if ($search->getPhotographer()) {
            $eventSearch = $eventSearch->where('user_id', $search->getPhotographer());
        }

        $allCategories = $search->getCategories();
        if ($allCategories) {
            $categories = [];
            foreach ($allCategories as $category) {
                $categories[] = $category->getId();
            }

            $eventSearch = $eventSearch->where('category_id', "%".implode(",", $categories)."%");
        }

        if ($eventSearch == null) {
            return [];
        }

        $eventSearch = $eventSearch->get();

        return $eventSearch->map(function ($item, $key) {
            $search = new Search($item->id, $item->name, $item->title);
            $search->changeEventdate($item->eventdate);

            return $search;
        })->toArray();
    }


}