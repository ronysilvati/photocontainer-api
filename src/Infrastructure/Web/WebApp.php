<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web;

interface WebApp
{
    public function bootstrap(array $conf);
    public function run();
}
