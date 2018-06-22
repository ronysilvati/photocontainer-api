<?php

use Phinx\Migration\AbstractMigration;

class PhotoAsCover extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('photos');
        $table->addColumn('cover', 'boolean', ['default' => false, 'after' => 'filename'])
            ->update();
    }
}
