<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Email\ReprovedEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\DisapprovalRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DisapprovalDownload
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
            $request = $this->repository->findDownloadRequest($event_id, $publisher_id);
            if ($request == null) {
                throw new \Exception('Pedido não localizado.');
            }

            if ($request->isActive() == false) {
                throw new \Exception('Pedido já negado.');
            }

            $request = $this->repository->disapproval($request);

            $this->sendEmail($event_id, $publisher_id);

            return new DisapprovalRequestResponse($request);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }

    public function sendEmail(int $event_id, int $publisher_id): void
    {
        try {
            $event = $this->repository->findEvent($event_id);
            $publisher = $this->repository->findUser($publisher_id);

            $data = [
                '{EVENT_NAME}' => $event->getName(),
            ];

            $email = new ReprovedEmail(
                $data,
                ['name' => $publisher->getName(), 'email' => $publisher->getEmail()],
                ['name' => getenv('PHOTOCONTAINER_EMAIL_NAME'), 'email' => getenv('PHOTOCONTAINER_EMAIL')]
            );
            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            //TODO Logar o erro no monolog, fazer nada para não impedir a ação.
        }
    }
}
