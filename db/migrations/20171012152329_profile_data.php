<?php

use Phinx\Migration\AbstractMigration;

class ProfileData extends AbstractMigration
{
    public function change()
    {
        $data = [
            [
                'name'    => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'photographer',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'publisher',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $profiles = $this->table('profiles');
        $profiles->insert($data)->save();
    }
}
