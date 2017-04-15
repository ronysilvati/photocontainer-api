<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\DownloadRequest;

class DownloadRequestResponse implements \JsonSerializable
{
    private $request;

    public function __construct(DownloadRequest $request)
    {
        $this->request = $request;
    }

    function jsonSerialize()
    {
        return [
            "id" => $this->request->getId(),
            "_links" => [
                "_self" => ['href' => "events/{$this->request->getEventId()}"],
            ],
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return 201;
    }
}