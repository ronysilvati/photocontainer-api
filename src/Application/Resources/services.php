<?php
$services = [];

$services['PhotoContainer\PhotoContainer\Contexts\*\Domain\*Repository'] = DI\object(
    'PhotoContainer\PhotoContainer\Contexts\*\Persistence\Eloquent*Repository'
);

return $services;