<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Suppliers;

class SuppliersUpdateResponse implements \JsonSerializable
{
    private $suppliers;

    public function __construct(Suppliers $suppliers)
    {
        $this->suppliers = $suppliers;
    }

    public function jsonSerialize()
    {
        return [
            'message' => 'Update realizado.',
            '_links' => [
                '_self' => ['href' => '/events/' .$this->suppliers->getId()],
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
