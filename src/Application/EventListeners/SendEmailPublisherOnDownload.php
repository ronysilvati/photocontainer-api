<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\PublisherDownloadEmail;

use PhotoContainer\PhotoContainer\Contexts\Photo\Domain\PhotoRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Event\DownloadedPhoto;

use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailDataLoader;
use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftPoolMailerHelper;

class SendEmailPublisherOnDownload extends AbstractListener
{
    /**
     * @var SwiftPoolMailerHelper
     */
    private $emailHelper;

    /**
     * @var EmailDataLoader
     */
    private $loader;

    /**
     * SendEmailPublisherOnDownload constructor.
     * @param SwiftPoolMailerHelper $emailHelper
     * @param EmailDataLoader $loader
     */
    public function __construct(SwiftPoolMailerHelper $emailHelper, EmailDataLoader $loader)
    {
        $this->emailHelper = $emailHelper;
        $this->loader = $loader;
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

            $publisher = $this->loader->getUserData($data->getPublisherId());

            $email = new PublisherDownloadEmail(
                null,
                ['name' => $publisher['name'], 'email' => $publisher['name']]
            );

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}