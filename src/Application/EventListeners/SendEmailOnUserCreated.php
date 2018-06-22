<?php

namespace PhotoContainer\PhotoContainer\Application\EventListeners;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use PhotoContainer\PhotoContainer\Application\Resources\Emails\NewUserEmail;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;

class SendEmailOnUserCreated extends AbstractListener
{
    /**
     * @var EmailHelper
     */
    private $emailHelper;

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

            $data = [
                '{NAME}' => $user->getName(),
                '{EMAIL}' => $user->getEmail(),
                '{PROFILE}' => $user->getProfile()->getProfileId() === Profile::PHOTOGRAPHER ? 'Fotografo' : 'Publisher',
                '{CREATIONDATE}' => date('d/m/y H:i:s')
            ];

            $email = new NewUserEmail(
                $data,
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