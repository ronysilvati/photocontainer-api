<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Emails;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class NewPublisherEmail extends Email
{
    /**
     * NewPublisherEmail constructor.
     * @param array|null $data
     * @param array $to
     * @param array|null $from
     */
    public function __construct(?array $data, array $to, ?array $from = null)
    {
        $file = __DIR__. '/templates/new_publisher.html';
        $subject = '[Publisher] Bem vinda!';

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
