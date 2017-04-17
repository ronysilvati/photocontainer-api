<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Email;

interface EmailHelper
{
    public function send(Email $email);
}
