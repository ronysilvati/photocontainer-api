<?php

use Phinx\Migration\AbstractMigration;

class PublisherPublication extends AbstractMigration
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
        $table = $this->table('publisher_publications');
        $table->addColumn('publisher_id', 'integer')
            ->addForeignKey('publisher_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('ask_for_changes', 'boolean', ['default' => false])
            ->addColumn('approved', 'boolean', ['default' => false])
            ->addColumn('visualized', 'boolean', ['default' => false])
            ->addColumn('message', 'text')
            ->addIndex(['publisher_id', 'event_id', 'visualized'])
            ->addTimestamps()
            ->create();
    }
}
