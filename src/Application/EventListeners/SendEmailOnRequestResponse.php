<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\ApprovedEmail;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\ReprovedEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Event\DownloadApproval;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;

class SendEmailOnRequestResponse extends AbstractListener
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
            /** @var DownloadApproval $eventData */
            $eventData = $event->getData();

            $event = $this->repository->findEvent($eventData->getEventId());
            $publisher = $this->repository->findUser($eventData->getPublisherId());

            $data = [
                '{EVENT_NAME}' => $event->getName(),
            ];

            if ($eventData->isApproved()) {
                $email = new ApprovedEmail(
                    $data,
                    ['name' => $publisher->getName(), 'email' => $publisher->getEmail()]
                );
            } else {
                $email = new ReprovedEmail(
                    $data,
                    ['name' => $publisher->getName(), 'email' => $publisher->getEmail()]
                );
            }

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}