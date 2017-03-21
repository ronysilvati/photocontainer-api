<?php

use Phinx\Migration\AbstractMigration;

class EventSearchView extends AbstractMigration
{
    public function up()
    {
        $sql = "DROP VIEW IF EXISTS event_search;
            CREATE VIEW event_search AS
            SELECT e.id, u.id as user_id, name, title,  date(eventdate) as eventdate, group_concat(c.description) as categories
            FROM users as u
                  INNER JOIN events as e
                    ON u.id = e.user_id
                  INNER JOIN event_categories as ec
                    ON e.id = ec.event_id
                  INNER JOIN categories as c
                    ON c.id = ec.category_id	    
            GROUP BY e.id;";

        $this->execute($sql);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute("DROP VIEW IF EXISTS event_search;");
    }
}
