<?php

namespace PhotoContainer\PhotoContainer\Application\Resources\Emails;

use PhotoContainer\PhotoContainer\Infrastructure\Email\Email;

class PublisherPublicationEmail extends Email
{
    /**
     * @var string
     */
    private $file = '/templates/publisher_publication.html';

    /**
     * @var string
     */
    private $subject = 'Publisher publicou seu álbum.';

    public function __construct(?array $data, array $to, ?array $from = null)
    {
        parent::__construct($data, __DIR__.$this->file, $this->subject, $to, $from);
    }
}
