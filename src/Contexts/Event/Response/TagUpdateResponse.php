<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Response;

class TagUpdateResponse
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function jsonSerialize()
    {
        return [
            'message' => 'Update realizado.',
            '_links' => [
                '_self' => ['href' => '/events/'],
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
