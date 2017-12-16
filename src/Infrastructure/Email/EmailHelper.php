<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

interface EmailHelper
{
    /**
     * @param Email $email
     * @return mixed
     */
    public function send(Email $email);
}
