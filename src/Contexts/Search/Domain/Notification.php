<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Domain;

class Notification
{
    /**
     * @var array
     */
    private $values = [];

    public function addNotification(string $name, int $value): void
    {
        $this->values[$name] = $value;
    }

    public function getTotal(): int
    {
        return array_reduce(
            $this->values,
            function ($carry, $item) {
                $carry += $item;
                return $carry;
            },
            0
        );
    }
}
