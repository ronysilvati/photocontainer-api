<?php

use Phinx\Migration\AbstractMigration;

class Event extends AbstractMigration
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
            ->addColumn('active', 'boolean', ['default' => true])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();

        $categories = $this->table('categories');
        $categories
            ->addColumn('description', 'string', ['limit' => 250, 'null' => true])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();

        $relation = $this->table('event_categories');
        $relation
            ->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('category_id', 'integer')
            ->addForeignKey('category_id', 'categories', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();
    }
}
