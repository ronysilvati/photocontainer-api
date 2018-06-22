<?php

namespace PhotoContainer\PhotoContainer\Contexts\Contact\Email;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class TotalContactsEmail extends Email
{
    public function __construct(?array $data, array $to, array $from)
    {
        $file = __DIR__ . '/templates/total_contacts.html';
        $subject = 'FOTO CONTAINER - Vagas atingidas.';

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
