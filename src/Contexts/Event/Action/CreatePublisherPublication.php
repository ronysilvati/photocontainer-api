<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;

use PhotoContainer\PhotoContainer\Contexts\Event\Command\CreatePublisherPublicationCommand;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\PublisherPublication;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\PublisherPublicationRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\PublisherPublicationResponse;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;

class CreatePublisherPublication
{
    /**
     * @var PublisherPublicationRepository
     */
    private $publicationRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * CreatePublisherPublication constructor.
     * @param PublisherPublicationRepository $publicationRepository
     * @param EventRepository $eventRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        PublisherPublicationRepository $publicationRepository,
        EventRepository $eventRepository,
        UserRepository $userRepository
    ) {
        $this->publicationRepository = $publicationRepository;
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param CreatePublisherPublicationCommand $command
     * @return PublisherPublicationResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     */
    public function handle(CreatePublisherPublicationCommand $command): PublisherPublicationResponse
    {
        if (!$this->eventRepository->find($command->getEventId())) {
            throw new DomainViolationException('O evento é inválido.');
        }

        if (!$this->userRepository->findPublisher(new Publisher($command->getPublisherId(), 3, ''))) {
            throw new DomainViolationException('O publisher é inválido.');
        }

        $publisherPublication = new PublisherPublication(
            null,
            $command->getEventId(),
            $command->getPublisherId(),
            $command->getText(),
            $command->isAskForChanges(),
            $command->isApproved()
        );

        $this->publicationRepository->create($publisherPublication);

        return new PublisherPublicationResponse($publisherPublication);
    }
}