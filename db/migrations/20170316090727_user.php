<?php

use Phinx\Migration\AbstractMigration;

class User extends AbstractMigration
{

    public function change()
    {
        $users = $this->table('users');
        $users->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('password', 'text')
            ->addColumn('email', 'string', ['limit' => 100])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['name'])
            ->addTimestamps()
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
            ->addColumn('birth', 'date', ['null' => true])
            ->addTimestamps()
            ->create();
    }
}
