<?php

use Phinx\Migration\AbstractMigration;

class Profile extends AbstractMigration
{
    public function change()
    {
        $profiles = $this->table('profiles');
        $profiles->addColumn('name', 'string', ['limit' => 250])
            ->addTimestamps()
            ->create();

        $userProfiles = $this->table('user_profiles');
        $userProfiles->addColumn('user_id', 'integer')
            ->addColumn('profile_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addForeignKey('profile_id', 'profiles', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('active', 'boolean')
            ->addTimestamps()
            ->create();
    }
}
