<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Email\ReprovedEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\DisapprovalRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventGeneratorTrait;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class DisapprovalDownload
{
    use EventGeneratorTrait;

    /**
     * @var ApprovalRepository
     */
    private $repository;

    /**
     * DisapprovalDownload constructor.
     * @param ApprovalRepository $repository
     */
    public function __construct(ApprovalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     * @return DisapprovalRequestResponse|DomainExceptionResponse
     */
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

            $this->sendEmailToPublisher($event_id, $publisher_id);

            return new DisapprovalRequestResponse($request);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
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

        $email = new ReprovedEmail(
            $data,
            ['name' => $publisher->getName(), 'email' => $publisher->getEmail()]
        );
        $this->addEvent('generic.sendemail', $email);
    }
}
