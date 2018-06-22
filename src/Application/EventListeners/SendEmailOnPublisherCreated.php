<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\NewPublisherEmail;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;
use Psr\Log\LoggerInterface;

class SendEmailOnPublisherCreated extends AbstractListener
{
    /**
     * @var EmailHelper
     */
    private $emailHelper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SendEmail constructor.
     * @param EmailHelper $emailHelper
     */
    public function __construct(EmailHelper $emailHelper)
    {
        $this->emailHelper = $emailHelper;
    }

    /**
     * @param EventInterface $event
     * @throws \Exception
     */
    public function handle(EventInterface $event): void
    {
        try {
            /** @var User $user */
            $user = $event->getData()->getUser();

            $email = new NewPublisherEmail(
                null,
                ['name' => $user->getName(), 'email' => $user->getEmail()],
                [
                    'name' => getenv('PHOTOCONTAINER_EMAIL_NAME'),
                    'email' => getenv('PHOTOCONTAINER_EMAIL')
                ]
            );

            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}