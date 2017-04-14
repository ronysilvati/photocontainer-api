<?php

use Phinx\Migration\AbstractMigration;

class AlterTableUser extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table('user_details')
            ->renameColumn('linkedin', 'pinterest')
            ->removeColumn('gender')
            ->addColumn('studio_name', 'string', ['limit' => 250, 'null' => true, 'after' => 'user_id'])
            ->addColumn('bio', 'text', ['null' => true, 'after' => 'birth'])
            ->addColumn('name_by', 'enum', ['values' => ['name', 'studio'], 'default' => 'name', 'after' => 'user_id'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('user_details')
            ->renameColumn('pinterest', 'linkedin')
            ->addColumn('gender', 'string', ['limit' => 1, 'null' => true])
            ->removeColumn('studio_name')
            ->removeColumn('bio')
            ->removeColumn('name_by')
            ->save();
    }
}

