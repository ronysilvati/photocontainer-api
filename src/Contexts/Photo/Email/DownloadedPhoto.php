<?php

namespace PhotoContainer\PhotoContainer\Contexts\Photo\Email;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class DownloadedPhoto extends Email
{
    public function __construct(array $data, array $to, ?array $from = null)
    {
        $file = __DIR__."/templates/downloaded_photo.html";
        $subject = "Foto baixada";

        parent::__construct($data, $file, $subject, $to, $from);
    }
}
