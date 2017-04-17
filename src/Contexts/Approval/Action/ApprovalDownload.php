<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Approval\Email\ApprovedEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\ApprovalRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class ApprovalDownload
{
    /**
     * @var ApprovalRepository
     */
    private $repository;

    /**
     * @var EmailHelper
     */
    private $emailHelper;

    /**
     * ApprovalDownload constructor.
     * @param ApprovalRepository $repository
     * @param EmailHelper $emailHelper
     */
    public function __construct(ApprovalRepository $repository, EmailHelper $emailHelper)
    {
        $this->repository = $repository;
        $this->emailHelper = $emailHelper;
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     * @return ApprovalRequestResponse|DomainExceptionResponse
     */
    public function handle(int $event_id, int $publisher_id)
    {
        try {
            $request = $this->repository->findDownloadRequest($event_id, $publisher_id);
            if ($request == null) {
                throw new \Exception('Pedido não localizado.');
            }

            if ($request->isAuthorized()) {
                throw new \Exception('Pedido já autorizado.');
            }

            $request = $this->repository->approval($request);

            $this->sendEmail($event_id, $publisher_id);

            return new ApprovalRequestResponse($request);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     */
    public function sendEmail(int $event_id, int $publisher_id): void
    {
        try {
            $event = $this->repository->findEvent($event_id);
            $publisher = $this->repository->findUser($publisher_id);

            $data = [
                '{EVENT_NAME}' => $event->getName(),
            ];

            $email = new ApprovedEmail(
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
