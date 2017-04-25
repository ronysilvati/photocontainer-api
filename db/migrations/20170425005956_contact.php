<?php

use Phinx\Migration\AbstractMigration;

class Contact extends AbstractMigration
{

    public function change()
    {
        $requests = $this->table('contacts');
        $requests->addColumn('name', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('email', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('phone', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('blog', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('profile', 'string', ['limit' => 150, 'null' => true])
            ->addTimestamps()
            ->create();
    }
}
