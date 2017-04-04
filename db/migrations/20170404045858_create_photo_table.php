<?php

use Phinx\Migration\AbstractMigration;

class CreatePhotoTable extends AbstractMigration
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
        $photos = $this->table('photos');
        $photos->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('name', 'string', ['limit' => 60, 'null' => false])
            ->addColumn('uuid', 'string', ['limit' => 36, 'null' => false])
            ->addColumn('downloads', 'integer', ['signed' => 'unsigned'])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();
    }
}
