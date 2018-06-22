<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Helper;

use Interop\Queue\PsrContext;
use Interop\Queue\PsrQueue;
use \Exception;

class EnqueueHelper
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
     * EnqueueHelper constructor.
     * @param PsrContext $context
     */
    public function __construct(PsrContext $context)
    {
        $this->context = $context;
    }

    /**
     * @param mixed $queue
     * @return $this
     */
    public function createQueue($queue)
    {
        if ($queue instanceof PsrQueue === false) {
            $queue = $this->context->createQueue($queue);
        }

        $this->queue = $queue;
        return $this;
    }

    /**
     * @param $message
     * @param string $queue
     * @throws \RuntimeException
     */
    public function queueMessage($message, string $queue): void
    {
        try {
            if (!$this->queue && $queue) {
                $this->createQueue($queue);
            }

            $message = $this->context->createMessage($message);

            $this->context->createProducer()->send($this->queue, $message);
        } catch (Exception $e) {
            throw new \RuntimeException(sprintf('Unable to send message to message queue.'), null, $e);
        }
    }
}