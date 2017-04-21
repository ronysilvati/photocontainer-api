<?php

use Phinx\Migration\AbstractMigration;

class EventTags extends AbstractMigration
{
    public function change()
    {
        $categories = $this->table('event_tags');
        $categories
            ->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('tag_id', 'integer')
            ->addForeignKey('tag_id', 'tags', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addTimestamps()
            ->create();
    }
}
