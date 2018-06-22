<?php

use Phinx\Migration\AbstractMigration;

class Address extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $address = $this->table('address');
        $address->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('zipcode', 'string', ['limit' => 50])
            ->addColumn('country', 'string', ['limit' => 150])
            ->addColumn('state', 'string', ['limit' => 150])
            ->addColumn('city', 'string', ['limit' => 150])
            ->addColumn('neighborhood', 'string', ['limit' => 150])
            ->addColumn('street', 'string', ['limit' => 150])
            ->addColumn('complement', 'string', ['limit' => 150])
            ->addTimestamps()
            ->create();
    }
}
