<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\ApprovalRequestEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Event\DownloadRequested;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailDataLoader;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;
use PhotoContainer\PhotoContainer\Infrastructure\SearchEngine\SearchEngine;

class SendEmailOnDownloadRequest extends AbstractListener
{
    /**
     * @var EmailDataLoader
     */
    private $loader;

    /**
     * @var SearchEngine
     */
    private $emailHelper;

    /**
     * SendEmailOnDownloadRequest constructor.
     * @param EmailHelper $emailHelper
     * @param EmailDataLoader $loader
     */
    public function __construct(EmailHelper $emailHelper, EmailDataLoader $loader)
    {
        $this->emailHelper = $emailHelper;
        $this->loader = $loader;
    }

    /**
     * @param EventInterface $event
     * @throws \Exception
     */
    public function handle(EventInterface $event): void
    {
        try {
            /** @var DownloadRequested $eventData */
            $eventData = $event->getData();

            $event = $this->loader->getEventData($eventData->getEventId());
            $publisher = $this->loader->getUserData($eventData->getPublisherId());
            $photographer = $this->loader->getUserData($event['user_id']);

            $data = [
                '{EVENT_NAME}' => $event['title'],
                '{PUBLISHER}' => $publisher['name']
            ];

            $email = new ApprovalRequestEmail(
                $data,
                ['name' => $photographer['name'], 'email' => $photographer['email']]
            );

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}