<?php

use Phinx\Migration\AbstractMigration;

class Supplier extends AbstractMigration
{
    public function change()
    {
        $details = $this->table('event_suppliers');
        $details
            ->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('suppliers', 'text', ['null' => true])
            ->addTimestamps()
            ->create();
    }
}
