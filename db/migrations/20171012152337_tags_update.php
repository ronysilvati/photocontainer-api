<?php

use Phinx\Migration\AbstractMigration;

class TagsUpdate extends AbstractMigration
{

    public function change()
    {
        $data = [
            [
                'tag_category_id' => 9,
                'description' => 'Nenhuma',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tag_category_id' => 5,
                'description' => 'Outro',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tag_category_id' => 1,
                'description' => 'Outro',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $tags = $this->table('tags');
        $tags->insert($data)->save();

        $this->execute("UPDATE tags SET description = 'Nenhuma' WHERE id = 49");
    }
}
