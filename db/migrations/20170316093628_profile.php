<?php

use Phinx\Migration\AbstractMigration;

class Profile extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $profiles = $this->table('profiles');
        $profiles->addColumn('name', 'string', ['limit' => 250])
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();

        $userProfiles = $this->table('user_profiles');
        $userProfiles->addColumn('user_id', 'integer')
            ->addColumn('profile_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addForeignKey('profile_id', 'profiles', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
            ->addColumn('active', 'boolean')
            ->addColumn('created_at', 'timestamp')
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();
    }
}
