<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Emails;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class NewUserEmail extends Email
{
    public function __construct(?array $data, array $to, ?array $from = null)
    {
        $file = __DIR__. '/templates/new_user.html';
        $subject = 'Novo usuário cadastrado.';

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
