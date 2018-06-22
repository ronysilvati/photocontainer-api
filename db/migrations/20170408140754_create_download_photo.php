<?php

use Phinx\Migration\AbstractMigration;

class CreateDownloadPhoto extends AbstractMigration
{
    public function change()
    {
        $photos = $this->table('downloads');
        $photos->addColumn('photo_id', 'integer')
            ->addForeignKey('photo_id', 'photos', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addIndex(['photo_id', 'user_id'])
            ->addTimestamps()
            ->create();
    }
}
