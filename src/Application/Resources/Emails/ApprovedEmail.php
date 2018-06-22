<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Emails;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class ApprovedEmail extends Email
{
    public function __construct(?array $data, array $to, ?array $from = null)
    {
        $file = __DIR__. '/templates/approved.html';
        $subject = 'Acesso Aprovado.';

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
