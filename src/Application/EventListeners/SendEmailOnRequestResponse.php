<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\ApprovedEmail;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\ReprovedEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Event\DownloadApproval;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailDataLoader;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;

class SendEmailOnRequestResponse extends AbstractListener
{
    /**
     * @var EmailDataLoader
     */
    private $loader;

    /**
     * @var ApprovalRepository
     */
    private $emailHelper;

    /**
     * SendEmailOnRequestResponse constructor.
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
            /** @var DownloadApproval $eventData */
            $eventData = $event->getData();

            $event = $this->loader->getEventData($eventData->getEventId());
            $publisher = $this->loader->getUserData($eventData->getPublisherId());

            $data = [
                '{EVENT_NAME}' => $event['title'],
            ];

            if ($eventData->isApproved()) {
                $email = new ApprovedEmail(
                    $data,
                    ['name' => $publisher['name'], 'email' => $publisher['email']]
                );
            } else {
                $email = new ReprovedEmail(
                    $data,
                    ['name' => $publisher['name'], 'email' => $publisher['email']]
                );
            }

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}