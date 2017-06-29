<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

use Whoops\Example\Exception;

class SwiftMailerHelper implements EmailHelper
{
    private $transport;

    public function __construct($transport)
    {
        $this->transport = $transport;
    }

    public function send(Email $email)
    {
        try {
            $replacements = [$email->getTo()['email'] => $email->getData()];

            // Create an instance of the plugin and register it
            $plugin = new \Swift_Plugins_DecoratorPlugin($replacements);
            $mailer = new \Swift_Mailer($this->transport);
            $mailer->registerPlugin($plugin);

            // Create the message
            $message = new \Swift_Message();
            $message->setSubject($email->getSubject());
            $message->setBody($email->getTemplate(), 'text/html');
            $message->setFrom($email->getFrom()['email'], $email->getFrom()['name']);

            $message->setTo($email->getTo()['email'], $email->getTo()['name']);
            $mailer->send($message);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
