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
                'description'    => 'Categoria 1',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Categoria 2',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'description'    => 'Categoria 3',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $profiles = $this->table('categories');
        $profiles->insert($data)->save();
    }
}
