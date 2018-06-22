<?php

use Phinx\Migration\AbstractMigration;

class Event extends AbstractMigration
{
    public function change()
    {
        $details = $this->table('events');
        $details
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('groom', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('bride', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('eventdate', 'datetime', ['null' => true])
            ->addColumn('title', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('terms', 'boolean', ['null' => true])
            ->addColumn('approval_general', 'boolean', ['null' => true])
            ->addColumn('approval_photographer', 'boolean', ['null' => true])
            ->addColumn('approval_bride', 'boolean', ['null' => true])
            ->addColumn('country', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('state', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('city', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('active', 'boolean', ['default' => true])
            ->addColumn('status', 'enum', ['values' => ['draft', 'finalized'], 'default' => 'draft'])
            ->addIndex(['title'])
            ->addTimestamps()
            ->create();

        $categories = $this->table('categories');
        $categories
            ->addColumn('description', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('active', 'boolean', ['default' => true])
            ->addColumn('order', 'integer', ['default' => null])
            ->addTimestamps()
            ->create();

        $relation = $this->table('event_categories');
        $relation
            ->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('category_id', 'integer')
            ->addForeignKey('category_id', 'categories', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addTimestamps()
            ->create();
    }
}
