<?php

use Phinx\Migration\AbstractMigration;

class AddNewTags extends AbstractMigration
{
    public function change()
    {
        $data = [
            [
                'tag_category_id' => 10,
                'description'     => 'Nenhuma',
                'created_at'      => date('Y-m-d H:i:s'),
            ],
        ];
        $profiles = $this->table('tags');

        $profiles->insert($data)->save();

        $this->execute('UPDATE tags SET description = \'Presbiteriano\' where id = 38;');
    }
}
