<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\PublisherPublication;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\PublisherPublicationRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\PublisherPublication as PublisherPublicationModel;

class EloquentPublisherPublicationRepository implements PublisherPublicationRepository
{
    public function create(PublisherPublication $publisherPublication)
    {
        try {
            $model = new PublisherPublicationModel();
            $model->publisher_id = $publisherPublication->getPublisherId();
            $model->event_id = $publisherPublication->getEventId();
            $model->ask_for_changes = $publisherPublication->getAskForChanges();
            $model->approved = $publisherPublication->getApproved();
            $model->save();

            $publisherPublication->setId($model->id);
            return $publisherPublication;
        } catch (\Exception $e) {
            throw new PersistenceException($e->getMessage());
        }
    }
}
