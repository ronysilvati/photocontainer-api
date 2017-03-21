<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web\Slim;

class JsonErrorHandler
{
    public function __invoke($request, $response, $args) {
        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
    }
}