<?php

use Phinx\Migration\AbstractMigration;

class EventTags extends AbstractMigration
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
        $categories = $this->table('event_tags');
        $categories
            ->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('tag_id', 'integer')
            ->addForeignKey('tag_id', 'tags', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();
    }
}
