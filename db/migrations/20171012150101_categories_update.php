<?php

use Phinx\Migration\AbstractMigration;

class CategoriesUpdate extends AbstractMigration
{

    public function change()
    {
        $data = [
            [
                'description'    => 'Ensaios',
                'order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Newborn',
                'order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Mini-Wedding',
                'order' => 4,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Religioso',
                'order' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Civil',
                'order' => 6,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $profiles = $this->table('categories');
        $profiles->insert($data)->save();

        $this->execute("UPDATE categories SET description = 'Pre-Wedding' WHERE id = 3");
        $this->execute("UPDATE categories SET description = 'Wedding' WHERE id = 4");
        $this->execute('UPDATE categories SET active = 0 WHERE id IN (2, 5)');
    }
}
