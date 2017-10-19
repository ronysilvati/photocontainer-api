<?php

namespace PhotoContainer\PhotoContainer\Contexts\Approval\Response;

use PhotoContainer\PhotoContainer\Contexts\Approval\Domain\DownloadRequest;

class DownloadRequestResponse implements \JsonSerializable
{
    private $request;

    public function __construct(DownloadRequest $request)
    {
        $this->request = $request;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->request->getId(),
            '_links' => [
                '_self' => ['href' => "events/{$this->request->getEventId()}"],
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
