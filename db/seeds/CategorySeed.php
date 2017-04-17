<?php

use Phinx\Seed\AbstractSeed;

class CategorySeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'description'    => '15 anos',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Noivado',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Pré-wedding',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Casamento',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Trash the dress',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $profiles = $this->table('categories');
        $profiles->insert($data)->save();
    }
}
