<?php

namespace PhotoContainer\PhotoContainer\Contexts\Auth\Response;

class AuthenticatedResponse implements \JsonSerializable
{
    private $httpStatus = 200;
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function jsonSerialize()
    {
        return [
            'token' => $this->token
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
