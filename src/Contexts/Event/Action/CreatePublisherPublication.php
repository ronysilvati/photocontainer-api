<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Action;


use PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Publisher;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\PublisherPublication;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\PublisherPublicationRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\UserRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Response\PublisherPublicationResponse;

use PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException;
use Psr\Http\Message\RequestInterface;

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
     * @param RequestInterface $request
     * @return PublisherPublicationResponse
     * @throws \PhotoContainer\PhotoContainer\Infrastructure\Exception\DomainViolationException
     * @throws DomainViolationException
     */
    public function handle(RequestInterface $request): \PhotoContainer\PhotoContainer\Contexts\Event\Response\PublisherPublicationResponse
    {
        $data = json_decode($request->getBody()->getContents());

        if (!$this->eventRepository->find($data->event_id)) {
            throw new DomainViolationException('O evento é inválido.');
        }

        if (!$this->userRepository->findPublisher(new Publisher($data->publisher_id, 3, ''))) {
            throw new DomainViolationException('O publisher é inválido.');
        }

        $publisherPublication = new PublisherPublication(
            null,
            $data->event_id,
            $data->publisher_id,
            $data->text,
            $data->ask_for_changes,
            $data->approved
        );

        $this->publicationRepository->create($publisherPublication);

        return new PublisherPublicationResponse($publisherPublication);
    }
}