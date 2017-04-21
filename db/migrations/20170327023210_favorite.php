<?php

use Phinx\Migration\AbstractMigration;

class Favorite extends AbstractMigration
{
    public function change()
    {
        $categories = $this->table('event_favorites');
        $categories
            ->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addIndex(['user_id', 'event_id'], ['unique' => true])
            ->addTimestamps()
            ->create();
    }
}
