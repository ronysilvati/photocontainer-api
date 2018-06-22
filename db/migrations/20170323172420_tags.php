<?php

use Phinx\Migration\AbstractMigration;

class Tags extends AbstractMigration
{
    public function change()
    {
        $categories = $this->table('tag_categories');
        $categories
            ->addColumn('description', 'string', ['limit' => 250, 'null' => true])
            ->addTimestamps()
            ->create();

        $categories = $this->table('tags');
        $categories
            ->addColumn('tag_category_id', 'integer')
            ->addForeignKey('tag_category_id', 'tag_categories', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('description', 'string', ['limit' => 250, 'null' => true])
            ->addTimestamps()
            ->create();
    }
}
