<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Approval\Email\ApprovalRequestEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\DownloadRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class RequestDownload
{
    private $repository;
    private $emailHelper;

    public function __construct(ApprovalRepository $repository, EmailHelper $emailHelper)
    {
        $this->repository = $repository;
        $this->emailHelper = $emailHelper;
    }

    public function handle(int $event_id, int $publisher_id)
    {
        try {
            $dlRequest = $this->repository->findDownloadRequest($event_id, $publisher_id);
            if ($dlRequest) {
                throw new \Exception('Seu pedido para download ainda está sendo analisado.');
            }

            $dlRequest = new DownloadRequest(
                null,
                $event_id,
                $publisher_id,
                false,
                false,
                true
            );

            $event = $this->repository->createDownloadRequest($dlRequest);

            $this->sendEmail($event_id, $publisher_id);

            return new DownloadRequestResponse($event);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }

    public function sendEmail(int $event_id, int $publisher_id): void
    {
        try {
            $event = $this->repository->findEvent($event_id);
            $publisher = $this->repository->findUser($publisher_id);
            $photographer = $this->repository->findUser($event->getUserId());

            $data = [
                '{EVENT_NAME}' => $event->getName(),
                '{PUBLISHER}' => $publisher->getName()
            ];

            $email = new ApprovalRequestEmail(
                $data,
                ['name' => $photographer->getName(), 'email' => $photographer->getEmail()],
                ['name' => getenv('PHOTOCONTAINER_EMAIL_NAME'), 'email' => getenv('PHOTOCONTAINER_EMAIL')]
            );
            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            //TODO Logar o erro no monolog, fazer nada para não impedir a ação.
        }
    }
}
