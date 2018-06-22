<?php

use Phinx\Migration\AbstractMigration;

class AccessLog extends AbstractMigration
{

    public function change()
    {
        $requests = $this->table('access_logs');
        $requests
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addTimestamps()
            ->create();
    }
}
