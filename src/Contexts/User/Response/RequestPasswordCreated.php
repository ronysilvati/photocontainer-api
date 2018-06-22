<?php

namespace PhotoContainer\PhotoContainer\Contexts\User\Response;

use PhotoContainer\PhotoContainer\Contexts\User\Domain\RequestPassword;

class RequestPasswordCreated implements \JsonSerializable
{
    /**
     * @var RequestPassword
     */
    private $requestPassword;

    /**
     * RequestPasswordCreated constructor.
     * @param RequestPassword $requestPassword
     */
    public function __construct(RequestPassword $requestPassword)
    {
        $this->requestPassword = $requestPassword;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'valid_until' => $this->requestPassword->getValidUntil()->format('Y-d-m h:i')
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