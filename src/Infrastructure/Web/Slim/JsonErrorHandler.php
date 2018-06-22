<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web\Slim;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class JsonErrorHandler
{
    /**
     * @param ServerRequestInterface $req
     * @param ResponseInterface $res
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $req, ResponseInterface $res)
    {
        return $res
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
    }
}
