<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

use Interop\Queue\ExceptionInterface as PsrException;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrQueue;

class SwiftQueueSpool extends \Swift_ConfigurableSpool
{
    /**
     * @var PsrContext
     */
    private $context;

    /**
     * @var PsrQueue
     */
    private $queue;

    /**
     * In milliseconds
     * @var int
     */
    const TIMEOUT = 50;

    /**
     * @param PsrContext      $context
     * @param PsrQueue|string $queue
     */
    public function __construct(PsrContext $context, $queue = 'swiftmailer_spool')
    {
        $this->context = $context;

        if ($queue instanceof PsrQueue === false) {
            $queue = $this->context->createQueue($queue);
        }

        $this->queue = $queue;
    }

    /**
     * {@inheritdoc}
     * @throws \Swift_IoException
     */
    public function queueMessage(\Swift_Mime_Message $message)
    {
        try {
            $message = $this->context->createMessage(serialize($message));

            $this->context->createProducer()->send($this->queue, $message);
        } catch (PsrException $e) {
            throw new \Swift_IoException(sprintf('Unable to send message to message queue.'), null, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flushQueue(\Swift_Transport $transport, &$failedRecipients = null): ?int
    {
        try {
            $consumer = $this->context->createConsumer($this->queue);

            $isTransportStarted = false;

            $failedRecipients = (array) $failedRecipients;
            $count = 0;
            $time = time();

            while (true) {
                if ($psrMessage = $consumer->receive(self::TIMEOUT)) {
                    if ($isTransportStarted === false) {
                        $transport->start();
                        $isTransportStarted = true;
                    }

                    $message = unserialize($psrMessage->getBody());

                    $count += $transport->send($message, $failedRecipients);

                    $consumer->acknowledge($psrMessage);
                }

                if ($this->getMessageLimit() && $count >= $this->getMessageLimit()) {
                    break;
                }

                if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                    break;
                }
            }

            return $count;
        } catch (\Exception $e) {
            //@TODO Logar o erro
        }
    }

    /**
     * {@inheritdoc}
     */
    public function start(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function stop(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted(): bool
    {
        return true;
    }
}