<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Domain;

class Suppliers
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $event_id;

    /**
     * @var string
     */
    public $suppliers;

    /**
     * Suppliers constructor.
     * @param int|null $id
     * @param int|null $event_id
     * @param null|string $suppliers
     */
    public function __construct(?int $id, ?int $event_id, ?string $suppliers)
    {
        $this->id = $id;
        $this->suppliers = $suppliers;
        $this->event_id = $event_id;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function changeId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSuppliers(): ?string
    {
        return $this->suppliers;
    }

    /**
     * @param string $suppliers
     */
    public function changeSuppliers(string $suppliers): void
    {
        $this->suppliers = $suppliers;
    }
}
