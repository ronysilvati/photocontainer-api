<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Action\RequestDownload;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventTag;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Favorite;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Suppliers;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\DownloadRequest as RequestModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event as EventModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventCategory as EventCategoryModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventFavorite;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSuppliers;
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
            $eventModel->country = $event->getCountry();
            $eventModel->state = $event->getState();
            $eventModel->city = $event->getCity();

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

    public function saveEventTags(array $eventTags, int $id)
    {
        try {
            EventTagModel::where('event_id', $id)->delete();

            foreach ($eventTags as $tag) {
                EventTagModel::create(['event_id' => $tag->getEventId(), 'tag_id' => $tag->getTagId()]);
            }

            return $eventTags;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function saveEventSuppliers(string $suppliers, int $id)
    {
        try {
            $model = EventSuppliers::where('event_id', $id)->first();

            if (!$model) {
                $model = new EventSuppliers();
                $model->event_id = $id;
            }

            $model->suppliers = $suppliers;
            $model->save();

            return new Suppliers($model->id, $id, $model->suppliers);
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        try {
            $event = EventModel::find($id);
            $event->active = 0;
            $event->save();

            return true;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }

    public function update(int $id, array $data, Event $event): Event
    {
        try {
            $event->changeGroom($data['groom']);
            $event->changeBride($data['bride']);
            $event->changeTitle($data['title']);
            $event->changeDescription($data['description']);
            $event->changeEventDate($data['eventDate']);
            $event->changeTerms($data['terms']);
            $event->changeApprovalGeneral($data['approval_general']);
            $event->changeApprovalPhotographer($data['approval_photographer']);
            $event->changeApprovalBride($data['approval_bride']);
            $event->changeCountry($data['country']);
            $event->changeState($data['state']);
            $event->changeCity($data['city']);

            $allCategories = [];
            foreach ($data['categories'] as $category) {
                $allCategories[] = new EventCategory(null, $category);
            }
            $event->changeCategories($allCategories);

            $eventModel = EventModel::find($id);
            $eventModel->groom = $event->getGroom();
            $eventModel->bride = $event->getBride();
            $eventModel->eventdate = $event->getEventDate();
            $eventModel->title = $event->getTitle();
            $eventModel->description = $event->getDescription();
            $eventModel->terms = $event->getTerms();
            $eventModel->country = $event->getCountry();
            $eventModel->state = $event->getState();
            $eventModel->city = $event->getCity();
            $eventModel->approval_general = $event->getApprovalGeneral();
            $eventModel->approval_photographer = $event->getApprovalPhotographer();
            $eventModel->approval_bride = $event->getApprovalBride();
            $eventModel->user_id = $event->getPhotographer()->getId();
            $eventModel->save();

            EventCategoryModel::where(['event_id' => $event->getId()])->delete();

            $categories = $event->getCategories();
            foreach ($categories as $cat) {
                EventCategoryModel::create(['event_id' => $event->getId(), 'category_id' => $cat->getCategoryId()]);
            }

            return $event;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }

        // TODO: Implement update() method.
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

            $suppliersData = EventSuppliers::where('event_id', $id)->first();
            $suppliers = new Suppliers($suppliersData->id ?? null, $suppliersData->event_id ?? null, $suppliersData->suppliers ?? null);

            return new Event(
                $eventData['id'],
                $photographer,
                $eventData['bride'],
                $eventData['groom'],
                $eventData['eventdate'],
                $eventData['title'],
                $eventData['description'],
                $eventData['country'],
                $eventData['state'],
                $eventData['city'],
                $eventData['terms'],
                $eventData['approval_general'],
                $eventData['approval_photographer'],
                $eventData['approval_bride'],
                $categories,
                $tags,
                $suppliers
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

            $favorite->changeTotalLikes(EventFavorite::where('event_id', $favorite->getEventId())->count());
            $favorite->changeId($eventFavorite->id);

            return $favorite;
        } catch (\Exception $e) {
            throw new PersistenceException("Erro na criação do favorito!");
        }
    }

    public function removeFavorite(Favorite $favorite): Favorite
    {
        try {
            EventFavorite::where('event_id', $favorite->getEventId())
                ->where('user_id', $favorite->getPublisher()->getId())
                ->delete();

            $favorite->changeTotalLikes(EventFavorite::where('event_id', $favorite->getEventId())->count());

            return $favorite;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
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

    public function createDownloadRequest(DownloadRequest $request): DownloadRequest
    {
        try {
            $requestModel = new RequestModel();
            $requestModel->event_id = $request->getEventId();
            $requestModel->user_id = $request->getUserId();
            $requestModel->authorized = $request->isAuthorized();
            $requestModel->visualized = $request->isVisualized();
            $requestModel->active = $request->isActive();
            $requestModel->save();

            $request->changeId($requestModel->id);

            return $request;
        } catch (\Exception $e) {
            throw new PersistenceException("Erro na criação do pedido para acesso!");
        }
    }

    public function findDownloadRequest(int $event_id, int $user_id): ?DownloadRequest
    {
        try {
            $request = RequestModel::where('user_id', $user_id)
                ->where('event_id', $event_id)
                ->first();

            if ($request == null) {
                return null;
            }

            return new DownloadRequest(
                $request->id,
                $request->event_id,
                $request->user_id,
                $request->authorized,
                $request->visualized,
                $request->active
            );
        } catch (\Exception $e) {
            throw new PersistenceException("Erro na criação do pedido para acesso!");
        }
    }
}
