<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

class SwiftMailerHelper implements EmailHelper
{
    /**
     * @var \Swift_Transport
     */
    private $transport;

    public function __construct(\Swift_Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param Email $email
     * @throws \Exception
     */
    public function send(Email $email)
    {
        try {
            $from = $email->getFrom();
            $to = $email->getTo();

            $mailer = new \Swift_Mailer($this->transport);

            if ($email->getData()) {
                $replacements = [$to['email'] => $email->getData()];

                // Create an instance of the plugin and register it
                $plugin = new \Swift_Plugins_DecoratorPlugin($replacements);
                $mailer->registerPlugin($plugin);
            }

            // Create the message
            $message = new \Swift_Message();
            $message->setSubject($email->getSubject());
            $message->setBody($email->getTemplate(), 'text/html');
            $message->setFrom($from['email'], $from['name']);

            $message->setTo($to['email'], $to['name']);
            $mailer->send($message);

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
