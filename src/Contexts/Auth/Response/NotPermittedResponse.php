<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Response;

class NotPermittedResponse implements \JsonSerializable
{
    private $httpStatus = 401;
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function jsonSerialize()
    {
        return [
            'message' => $this->message
        ];
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
