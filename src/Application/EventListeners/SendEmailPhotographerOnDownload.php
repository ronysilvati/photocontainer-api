<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\PhotographerDownloadEmail;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Photo\Event\DownloadedPhoto;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\UserRepository;

use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftPoolMailerHelper;

class SendEmailPhotographerOnDownload extends AbstractListener
{
    /**
     * @var SwiftPoolMailerHelper
     */
    private $emailHelper;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * SendEmailPhotographerOnDownload constructor.
     * @param SwiftPoolMailerHelper $emailHelper
     * @param EventRepository $eventRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        SwiftPoolMailerHelper $emailHelper,
        EventRepository $eventRepository,
        UserRepository $userRepository
    ) {
        $this->emailHelper = $emailHelper;
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
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

            $event = $this->eventRepository->find($data->getEventId());
            $publisher = $this->userRepository->findUser($data->getPublisherId());
            $photographer = $this->userRepository->findUser($event->getPhotographer()->getId());

            $details = $publisher->getDetails();
            $blog = '<li><a href="'.$details->getBlog().'">'.$details->getBlog().'</a></li>';
            $facebook = '<li><a href="'.$details->getFacebook().'">'.$details->getFacebook().'</a></li>';
            $instagram = '<li><a href="'.$details->getInstagram().'">'.$details->getInstagram().'</a></li>';

            $email = new PhotographerDownloadEmail(
                [
                    '{PUBLISHER}' => $publisher->getName(),
                    '{PHOTOGRAPHER}' => $photographer->getName(),
                    '{EVENT}' => $event->getTitle(),
                    '{PUBLISHER_BLOG}' => $details->getBlog() ? $blog : '',
                    '{PUBLISHER_FACEBOOK}' => $details->getFacebook() ? $facebook : '',
                    '{PUBLISHER_INSTA}' => $details->getInstagram() ? $instagram : '',
                ],
                ['name' => $photographer->getName(), 'email' => $photographer->getEmail()]
            );

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}