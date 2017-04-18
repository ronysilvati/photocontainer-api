<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

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
            $mailer = \Swift_Mailer::newInstance($this->transport);
            $mailer->registerPlugin($plugin);

            // Create the message
            $message = \Swift_Message::newInstance();
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
