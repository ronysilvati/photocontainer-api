<?php

namespace PhotoContainer\PhotoContainer\Infrastructure;

use PhotoContainer\PhotoContainer\Infrastructure\Web\WebApp;

interface ContextBootstrap
{
    public function wireSlimRoutes(WebApp $slimApp): WebApp;
}
