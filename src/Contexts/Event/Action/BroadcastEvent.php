<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Application\Resources\Emails\BroadcastEventEmail;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\BroadcastResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Email\SwiftPoolMailerHelper;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;

class BroadcastEvent
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EmailHelper
     */
    private $emailHelper;

    /**
     * BroadcastEvent constructor.
     * @param EventRepository $eventRepository
     * @param UserRepository $userRepository
     * @param SwiftPoolMailerHelper $emailHelper
     */
    public function __construct(
        EventRepository $eventRepository,
        UserRepository $userRepository,
        SwiftPoolMailerHelper $emailHelper
    ) {
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
        $this->emailHelper = $emailHelper;
    }

    /**
     * @param int $event_id
     * @return BroadcastResponse
     * @throws DomainViolationException
     */
    public function handle(int $event_id): BroadcastResponse
    {
        $event = $this->eventRepository->find($event_id);
        if (!$event) {
            throw new DomainViolationException('Evento inexistente.');
        }

        $publishers = $this->userRepository->findByProfile(Publisher::APPROVED_PROFILE);

        $this->queueEmails($publishers, $event);

        return new BroadcastResponse();
    }

    public function queueEmails($publishers, $event)
    {
        $to = ['name' => getenv('PHOTOCONTAINER_EMAIL_NAME'), 'email' => getenv('PHOTOCONTAINER_EMAIL')];

        $funEmails = function ($publishers, $event) use ($to) {
            /** @var Publisher $publisher */
            foreach ($publishers as $publisher) {
                $this->eventRepository->createNotification($event, $publisher);

                $data = [
                    '{EVENT_NAME}' => $event->getTitle()
                ];

                yield new BroadcastEventEmail(
                    $data,
                    ['name' => $publisher->getName(), 'email' => $publisher->getEmail()],
                    $to
                );
            }
        };

        foreach ($funEmails($publishers, $event) as $email) {
            try {
                $this->emailHelper->send($email);
            } catch (\Exception $e) {
                //@TODO logar os erros no monolog
            }
        }
    }
}