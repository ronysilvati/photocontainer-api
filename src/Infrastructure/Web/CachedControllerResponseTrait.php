<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web;

use Psr\Http\Message\ResponseInterface;
use Slim\HttpCache\CacheProvider;

trait CachedControllerResponseTrait
{
    /**
     * @param ResponseInterface $response
     * @param $domainResponse
     * @return ResponseInterface
     */
    public function cachedHttpResponse(ResponseInterface $response, $domainResponse): ResponseInterface
    {
        $httpCache = $this->container->get(CacheProvider::class);

        $response = $httpCache->withExpires($response, getenv('HEAD_EXPIRES'));
        return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
    }
}