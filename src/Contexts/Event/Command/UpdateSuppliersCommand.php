<?php

namespace PhotoContainer\PhotoContainer\Contexts\Event\Command;

class UpdateSuppliersCommand
{
    /**
     * @var array
     */
    private $suppliers;

    /**
     * @var int
     */
    private $eventId;

    /**
     * UpdateSuppliersCommand constructor.
     * @param int $eventId
     * @param array $suppliers
     */
    public function __construct(int $eventId, array $suppliers)
    {
        $this->suppliers = $suppliers;
        $this->eventId = $eventId;
    }

    /**
     * @return string
     */
    public function getSuppliers(): string
    {
        return json_encode((object) $this->suppliers);
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }
}