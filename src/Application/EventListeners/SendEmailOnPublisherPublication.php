<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\PublisherPublicationEmail;
use PhotoContainer\PhotoContainer\Contexts\Event\Event\PublisherPublished;
use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftPoolMailerHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\Event;

class SendEmailOnPublisherPublication extends AbstractListener
{
    /**
     * @var SwiftPoolMailerHelper
     */
    private $emailHelper;

    /**
     * SendEmailOnPublisherPublication constructor.
     * @param SwiftPoolMailerHelper $emailHelper
     */
    public function __construct(SwiftPoolMailerHelper $emailHelper) {
        $this->emailHelper = $emailHelper;
    }

    /**
     * @param EventInterface $event
     * @throws \Exception
     */
    public function handle(EventInterface $event): void
    {
        try {
            /** @var PublisherPublished $eventData */
            $eventData = $event->getData();

            $event = Event::find($eventData->getEventId());
            $photographer = $event->user()->first();

            $data = [
                '{PUBLISHER_TEXT}' => $eventData->getText(),
            ];

            $email = new PublisherPublicationEmail(
                $data,
                ['name' => $photographer->name, 'email' => $photographer->email]
            );

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}