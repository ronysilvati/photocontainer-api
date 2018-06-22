<?php

use Phinx\Migration\AbstractMigration;

class DownloadRequest extends AbstractMigration
{
    public function change()
    {
        $requests = $this->table('download_requests');
        $requests
            ->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('authorized', 'boolean', ['default' => false])
            ->addColumn('visualized', 'boolean', ['default' => false])
            ->addColumn('active', 'boolean', ['default' => false])
            ->addIndex(['user_id', 'event_id'])
            ->addTimestamps()
            ->create();
    }
}
