<?php

use Phinx\Migration\AbstractMigration;

class AdressData extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('countries');
        $table->addColumn('name', 'string', ['limit' => 60])
            ->addIndex('name')
            ->create();

        $table = $this->table('states');
        $table->addColumn('country_id', 'integer')
            ->addForeignKey('country_id', 'countries', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('name', 'string', ['limit' => 60])
            ->addColumn('statecode', 'string', ['limit' => 10])
            ->addIndex('statecode')
            ->create();

        $table = $this->table('cities');
        $table->addColumn('state_id', 'integer')
            ->addForeignKey('state_id', 'states', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('name', 'string', ['limit' => 60])
            ->create();
    }
}
