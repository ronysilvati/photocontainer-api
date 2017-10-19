<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Emails;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class BroadcastEventEmail extends Email
{
    /**
     * BroadcastEventEmail constructor.
     * @param array|null $data
     * @param array $to
     * @param array|null $from
     */
    public function __construct(?array $data, array $to, ?array $from = null)
    {
        $file = __DIR__. '/templates/broadcast_event.html';
        $subject = 'Broadcast de evento.';

        parent::__construct($data, $file, $subject, $to, $from);
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }
}