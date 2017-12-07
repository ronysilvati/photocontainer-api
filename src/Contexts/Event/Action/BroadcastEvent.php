<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Application\Resources\Emails\BroadcastEventEmail;
use PhotoContainer\PhotoContainer\Contexts\Event\Command\BroadcastEventCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
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
     * @param BroadcastEventCommand $command
     * @return BroadcastResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     */
    public function handle(BroadcastEventCommand $command): BroadcastResponse
    {
        $event = $this->eventRepository->find($command->getEventId());
        if (!$event) {
            throw new DomainViolationException('Evento inexistente.');
        }

        $publishers = $this->userRepository->findByProfile(Publisher::APPROVED_PROFILE);

        $this->queueEmails($publishers, $event);

        return new BroadcastResponse();
    }

    public function queueEmails(array $publishers, Event $event): void
    {
        $to = ['name' => getenv('PHOTOCONTAINER_EMAIL_NAME'), 'email' => getenv('PHOTOCONTAINER_EMAIL')];

        $template = '';
        /** @var Publisher $publisher */
        foreach ($publishers as $publisher) {
            try {
                $email = new BroadcastEventEmail(
                    [
                        '{EVENT_NAME}' => $event->getTitle()
                    ],
                    ['name' => $publisher->getName(), 'email' => $publisher->getEmail()],
                    $to
                );

                if ($template === '') {
                    $template = $email->getTemplate();
                } else {
                    $email->setTemplate($template);
                }

                $this->emailHelper->send($email);
            } catch (\Exception $e) {
                //@TODO logar os erros no monolog
            }
        }
    }
}