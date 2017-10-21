<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\ApprovalRequestEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Event\DownloadRequested;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;

class SendEmailOnDownloadRequest extends AbstractListener
{
    /**
     * @var EmailHelper
     */
    private $emailHelper;

    /**
     * @var ApprovalRepository
     */
    private $repository;

    /**
     * SendEmailOnDownloadRequest constructor.
     * @param EmailHelper $emailHelper
     * @param ApprovalRepository $repository
     */
    public function __construct(EmailHelper $emailHelper, ApprovalRepository $repository)
    {
        $this->emailHelper = $emailHelper;
        $this->repository = $repository;
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

            $event = $this->repository->findEvent($eventData->getEventId());
            $publisher = $this->repository->findUser($eventData->getPublisherId());
            $photographer = $this->repository->findUser($event->getUserId());

            $data = [
                '{EVENT_NAME}' => $event->getName(),
                '{PUBLISHER}' => $publisher->getName()
            ];

            $email = new ApprovalRequestEmail(
                $data,
                ['name' => $photographer->getName(), 'email' => $photographer->getEmail()]
            );

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}