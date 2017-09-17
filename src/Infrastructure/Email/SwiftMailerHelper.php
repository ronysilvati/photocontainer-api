<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

class SwiftMailerHelper implements EmailHelper
{
    /**
     * @var \Swift_Transport
     */
    private $transport;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Transport $transport)
    {
        $this->transport = $transport;

        $this->mailer = new \Swift_Mailer($this->transport);
        $this->mailer->registerPlugin(new \Swift_Plugins_AntiFloodPlugin(100, 1));
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

            if ($email->getData()) {
                $replacements = [$to['email'] => $email->getData()];

                // Create an instance of the plugin and register it
                $plugin = new \Swift_Plugins_DecoratorPlugin($replacements);
                $this->mailer->registerPlugin($plugin);
            }

            // Create the message
            $message = new \Swift_Message();
            $message->setSubject($email->getSubject());
            $message->setBody($email->getTemplate(), 'text/html');
            $message->setFrom($from['email'], $from['name']);

            $message->setTo($to['email'], $to['name']);
            $this->mailer->send($message);

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
