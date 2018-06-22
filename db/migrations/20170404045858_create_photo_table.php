<?php

use Phinx\Migration\AbstractMigration;

class CreatePhotoTable extends AbstractMigration
{
    public function change()
    {
        $photos = $this->table('photos');
        $photos->addColumn('event_id', 'integer')
            ->addForeignKey('event_id', 'events', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('filename', 'string', ['limit' => 40, 'null' => false])
            ->addIndex(['filename', 'event_id'], ['unique' => true])
            ->addTimestamps()
            ->create();
    }
}
