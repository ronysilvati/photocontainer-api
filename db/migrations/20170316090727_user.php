<?php

use Phinx\Migration\AbstractMigration;

class User extends AbstractMigration
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
        $users = $this->table('users');
        $users->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('password', 'text')
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(array('email'), ['unique' => true])
            ->create();

        $details = $this->table('user_details');
        $details
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('blog', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('facebook', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('instagram', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('linkedin', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('site', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('gender', 'string', ['limit' => 1, 'null' => true])
            ->addColumn('phone', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('birth', 'datetime', ['null' => true])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();
    }
}
