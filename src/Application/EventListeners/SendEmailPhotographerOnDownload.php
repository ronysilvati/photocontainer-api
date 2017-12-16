<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\PhotographerDownloadEmail;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Event\DownloadedPhoto;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;

use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailDataLoader;
use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftPoolMailerHelper;

class SendEmailPhotographerOnDownload extends AbstractListener
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
     * SendEmailPhotographerOnDownload constructor.
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

            $event = $this->loader->getEventData($data->getEventId());
            $publisher = $this->loader->getUserData($data->getPublisherId());
            $photographer = $this->loader->getUserData($event->getPhotographer()->getId());

            $details = $publisher->getDetails();
            $blog = '<li><a href="'.$publisher['blog'].'">'.$publisher['blog'].'</a></li>';
            $facebook = '<li><a href="'.$publisher['facebook'].'">'.$publisher['facebook'].'</a></li>';
            $instagram = '<li><a href="'.$publisher['instagram'].'">'.$publisher['instagram'].'</a></li>';

            $email = new PhotographerDownloadEmail(
                [
                    '{PUBLISHER}' => $publisher['name'],
                    '{PHOTOGRAPHER}' => $photographer['name'],
                    '{EVENT}' => $event['title'],
                    '{PUBLISHER_BLOG}' => $publisher['blog'] ? $blog : '',
                    '{PUBLISHER_FACEBOOK}' => $publisher['facebook'] ? $facebook : '',
                    '{PUBLISHER_INSTA}' => $publisher['instagram'] ? $instagram : '',
                ],
                ['name' => $photographer['name'], 'email' => $photographer['email']]
            );

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}