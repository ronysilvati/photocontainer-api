<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventTag;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event as EventModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventCategory as EventCategoryModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventFavorite;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventTag as EventTagModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\User;

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
                EventCategoryModel::create(['event_id' => $event->getId(), 'category_id' => $cat->getCategoryId()]);
            }

            $tags = $event->getTags();
            foreach ($tags as $tag) {
                EventTagModel::create(['event_id' => $event->getId(), 'tag_id' => $tag->getTagId()]);
            }

            return $event;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function find(int $id): Event
    {
        try {
            $eventModel = EventModel::find($id);
            $eventData = $eventModel->load('EventCategory', 'EventTag', 'User')->toArray();

            $photographer = new Photographer($eventData['user']['id'], null, null);

            $categories = [];
            foreach ($eventData['event_category'] as $category) {
                $categories[] = new EventCategory($eventData['id'], $category['category_id']);
            }

            $tags = [];
            foreach ($eventData['event_tag'] as $tag) {
                $tags[] = new EventTag($eventData['id'], $tag['tag_id']);
            }

            return new Event(
                $eventData['id'],
                $photographer,
                $eventData['bride'],
                $eventData['groom'],
                $eventData['eventdate'],
                $eventData['title'],
                $eventData['description'],
                $eventData['terms'],
                $eventData['approval_general'],
                $eventData['approval_photographer'],
                $eventData['approval_bride'],
                $categories,
                $tags
            );
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function findPhotographer(Photographer $photographer)
    {
        try {
            $userData = $this->findUser($photographer->getId());
            $photographer->changeProfileId($userData['userprofile']['profile_id']);

            return $photographer;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function findPublisher(Publisher $publisher)
    {
        try {
            $userData = $this->findUser($publisher->getId());
            $publisher->changeProfileId($userData['userprofile']['profile_id']);

            return $publisher;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    private function findUser(int $id)
    {
        try {
            $userModel = User::find($id);
            $userModel->load('userprofile');
            return $userModel->toArray();
        } catch (\Exception $e) {
            throw new PersistenceException("O usuário não existe!");
        }
    }

    public function createFavorite(Favorite $favorite): Favorite
    {
        try {
            $eventFavorite = new EventFavorite();
            $eventFavorite->user_id = $favorite->getPublisher()->getId();
            $eventFavorite->event_id = $favorite->getEventId();
            $eventFavorite->save();

            $favorite->changeId($eventFavorite->id);
            return $favorite;
        } catch (\Exception $e) {
            throw new PersistenceException("Erro na criação do favorito!");
        }
    }

    public function removeFavorite(Favorite $favorite): bool
    {
        // TODO: Implement removeFavorite() method.
    }

    public function findFavorite(Favorite $favorite): Favorite
    {
        if ($favorite->getId()) {
            $data = EventFavorite::find($favorite->getId());
            $favorite->changeEventId($data['event_id']);
            $favorite->changePublisher(new Publisher($data['user_id'], null, null));

            return $favorite;
        }

        $data = EventFavorite::where([
            'event_id' => $favorite->getEventId(),
            'user_id' => $favorite->getPublisher()->getId(),
        ])->get('id')->first()->toArray();

        if ($data) {
            $favorite->changeId($data['id']);
            return $favorite;
        }
    }
}