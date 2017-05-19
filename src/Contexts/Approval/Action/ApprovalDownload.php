<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Email\ApprovedEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\ApprovalRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventGeneratorTrait;


class ApprovalDownload
{
    use EventGeneratorTrait;

    /**
     * @var ApprovalRepository
     */
    private $repository;

    /**
     * ApprovalDownload constructor.
     * @param ApprovalRepository $repository
     */
    public function __construct(ApprovalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     * @return ApprovalRequestResponse
     * @throws \Exception
     */
    public function handle(int $event_id, int $publisher_id)
    {
        $request = $this->repository->findDownloadRequest($event_id, $publisher_id);
        if ($request == null) {
            throw new \Exception('Pedido não localizado.');
        }

        if ($request->isAuthorized()) {
            throw new \Exception('Pedido já autorizado.');
        }

        $request = $this->repository->approval($request);

        $this->sendEmailToPublisher($event_id, $publisher_id);

        return new ApprovalRequestResponse($request);
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     */
    public function sendEmailToPublisher(int $event_id, int $publisher_id): void
    {
        $event = $this->repository->findEvent($event_id);
        $publisher = $this->repository->findUser($publisher_id);

        $data = [
            '{EVENT_NAME}' => $event->getName(),
        ];

        $email = new ApprovedEmail(
            $data,
            ['name' => $publisher->getName(), 'email' => $publisher->getEmail()]
        );
        $this->addEvent('generic.sendmail', $email);
    }
}
