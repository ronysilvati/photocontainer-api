<?php

use Phinx\Migration\AbstractMigration;

class FavoritePhoto extends AbstractMigration
{
    public function change()
    {
        $categories = $this->table('photo_favorites');
        $categories
            ->addColumn('photo_id', 'integer')
            ->addForeignKey('photo_id', 'photos', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addIndex(['user_id', 'photo_id'])
            ->addTimestamps()
            ->create();
    }
}
