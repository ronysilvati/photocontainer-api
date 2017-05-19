<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Listeners;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;
use PhotoContainer\PhotoContainer\Infrastructure\Email\EmailHelper;

class SendEmail
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
     * @param Email $email
     */
    public function __invoke(Email $email)
    {
        try {
            $this->emailHelper->send($email);
        } catch (\Exception $e) {
            //TODO Logar
        }
    }
}