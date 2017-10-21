<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\PublisherDownloadEmail;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Event\DownloadedPhoto;

use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftPoolMailerHelper;

class SendEmailPublisherOnDownload extends AbstractListener
{
    /**
     * @var SwiftPoolMailerHelper
     */
    private $emailHelper;

    /**
     * @var PhotoRepository
     */
    private $dbRepo;

    /**
     * SendEmailPublisherOnDownload constructor.
     * @param SwiftPoolMailerHelper $emailHelper
     * @param PhotoRepository $dbRepo
     */
    public function __construct(SwiftPoolMailerHelper $emailHelper, PhotoRepository $dbRepo)
    {
        $this->emailHelper = $emailHelper;
        $this->dbRepo = $dbRepo;
    }

    /**
     * @param EventInterface $event
     * @throws \Exception
     */
    public function handle(EventInterface $event): void
    {
        try {
            /** @var DownloadedPhoto $data */
            $data = $event->getData();

            $publisher = $this->dbRepo->findPublisher($data->getPublisherId());

            $email = new PublisherDownloadEmail(
                null,
                ['name' => $publisher->getName(), 'email' => $publisher->getEmail()]
            );

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}