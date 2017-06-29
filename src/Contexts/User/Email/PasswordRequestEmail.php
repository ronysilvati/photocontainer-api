<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Email;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class PasswordRequestEmail extends Email
{
    public function __construct(array $data, array $to, ?array $from = null)
    {
        $file = __DIR__."/templates/new_user.html";
        $subject = "Requisição de alteração de senha.";

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
