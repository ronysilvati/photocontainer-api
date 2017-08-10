<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\PublisherDownloadEmail;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\Download;
use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Event\DownloadedPhoto;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;

class SendEmailPublisherOnDownload extends AbstractListener
{
    /**
     * @var EmailHelper
     */
    private $emailHelper;

    /**
     * @var PhotoRepository
     */
    private $dbRepo;

    /**
     * SendEmailPublisherOnDownload constructor.
     * @param EmailHelper $emailHelper
     * @param PhotoRepository $dbRepo
     */
    public function __construct(EmailHelper $emailHelper, PhotoRepository $dbRepo)
    {
        $this->emailHelper = $emailHelper;
        $this->dbRepo = $dbRepo;
    }

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
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
            //
        }
    }
}