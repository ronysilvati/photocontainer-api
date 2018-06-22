<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Emails;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class PublisherDownloadEmail extends Email
{
    /**
     * PublisherDownloadEmail constructor.
     * @param array|null $data
     * @param array $to
     * @param array|null $from
     */
    public function __construct(?array $data, array $to, ?array $from = null)
    {
        $file = __DIR__. '/templates/publisher_download_email.html';
        $subject = '[Publisher] Foto baixada';

        parent::__construct($data, $file, $subject, $to, $from);
    }
}