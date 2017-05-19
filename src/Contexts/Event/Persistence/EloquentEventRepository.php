<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventTag;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Suppliers;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event as EventModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventCategory as EventCategoryModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventSuppliers;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\EventTag as EventTagModel;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;

class EloquentEventRepository implements EventRepository
{
    /**
     * @var EloquentDatabaseProvider
     */
    private $conn;

    public function __construct(EloquentDatabaseProvider $conn)
    {
        $this->conn = $conn;
    }

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
}
