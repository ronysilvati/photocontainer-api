<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Emails;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class ApprovalRequestEmail extends Email
{
    public function __construct(?array $data, array $to, ?array $from = null)
    {
        $file = __DIR__. '/templates/approval_request.html';
        $subject = 'Pedido de aprovação.';

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
