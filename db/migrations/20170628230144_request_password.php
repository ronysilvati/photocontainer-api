<?php

use Phinx\Migration\AbstractMigration;

class RequestPassword extends AbstractMigration
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
        $users = $this->table('request_passwords');
        $users->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('token', 'string', ['limit' => 37])
            ->addColumn('active', 'boolean', ['default' => true])
            ->addColumn('valid_until', 'datetime', ['null' => false])
            ->addIndex(['token'])
            ->addIndex(['user_id'], ['unique' => true])
            ->addTimestamps()
            ->create();
    }
}
