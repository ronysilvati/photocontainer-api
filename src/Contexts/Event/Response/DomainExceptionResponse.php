<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

class DomainExceptionResponse
{
    private $httpStatus = 500;
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    function jsonSerialize()
    {
        return [
            "message" => $this->message
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