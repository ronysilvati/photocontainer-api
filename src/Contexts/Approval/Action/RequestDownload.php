<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Action;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\ApprovalRepository;
use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;
use PhotoContainer\PhotoContainer\Contexts\Approval\Email\ApprovalRequestEmail;
use PhotoContainer\PhotoContainer\Contexts\Approval\Response\DownloadRequestResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Event\EventGeneratorTrait;
use PhotoContainer\PhotoContainer\Infrastructure\Web\DomainExceptionResponse;

class RequestDownload
{
    use EventGeneratorTrait;

    /**
     * @var ApprovalRepository
     */
    private $repository;

    /**
     * RequestDownload constructor.
     * @param ApprovalRepository $repository
     */
    public function __construct(ApprovalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     * @return DownloadRequestResponse|DomainExceptionResponse
     */
    public function handle(int $event_id, int $publisher_id)
    {
        try {
            $dlRequest = $this->repository->findDownloadRequest($event_id, $publisher_id);
            if ($dlRequest) {
                $msg = !$dlRequest->isActive() && !$dlRequest->isAuthorized() ? "Seu pedido não foi autorizado." : 'Seu pedido para download ainda está sendo analisado.';
                throw new \Exception($msg);
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

            $this->sendEmailToPhotographer($event_id, $publisher_id);

            return new DownloadRequestResponse($event);
        } catch (\Exception $e) {
            return new DomainExceptionResponse($e->getMessage());
        }
    }

    /**
     * @param int $event_id
     * @param int $publisher_id
     */
    public function sendEmailToPhotographer(int $event_id, int $publisher_id): void
    {
        $event = $this->repository->findEvent($event_id);
        $publisher = $this->repository->findUser($publisher_id);
        $photographer = $this->repository->findUser($event->getUserId());

        $data = [
            '{EVENT_NAME}' => $event->getName(),
            '{PUBLISHER}' => $publisher->getName()
        ];

        $email = new ApprovalRequestEmail(
            $data,
            ['name' => $photographer->getName(), 'email' => $photographer->getEmail()]
        );
        $this->addEvent('generic.sendemail', $email);
    }
}
