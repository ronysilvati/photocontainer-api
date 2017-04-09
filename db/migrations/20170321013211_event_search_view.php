<?php

use Phinx\Migration\AbstractMigration;

class EventSearchView extends AbstractMigration
{
    public function up()
    {
        $this->execute("DROP VIEW IF EXISTS event_search;
                CREATE VIEW event_search AS
                SELECT e.id, 
                         u.id as user_id,
                         name,
                         title,
                         date(eventdate) as eventdate,
                         c.id as category_id,
                         GROUP_CONCAT(c.description) as category,
                         t.id as tag_id,
                         (SELECT COUNT(id) as total FROM photos WHERE e.id = event_id) as photos,
                         (SELECT COUNT(id) as total FROM event_favorites WHERE e.id = event_id) as likes
                FROM users as u
                    INNER JOIN events as e
                      ON u.id = e.user_id
                    INNER JOIN event_categories as ec
                      ON e.id = ec.event_id
                    INNER JOIN categories as c
                      ON c.id = ec.category_id	    
                    LEFT JOIN event_tags as et
                      ON e.id = et.event_id
                    LEFT JOIN tags as t
                      ON t.id = et.tag_id
                WHERE e.active = 1      	 
                GROUP BY e.id, c.id, t.id;");

        $this->execute("
                DROP VIEW IF EXISTS event_search_publisher;
                CREATE VIEW event_search_publisher AS
                SELECT
                     e.id,
                     u.id as user_id,
                     name,
                     title,
                     date(eventdate) as eventdate,
                     c.id as category_id,
                     GROUP_CONCAT(c.description) as category,
                     t.id as tag_id,
                     (SELECT COUNT(id) as total FROM photos WHERE e.id = event_id) as photos,
                     (SELECT COUNT(id) as total FROM event_favorites WHERE e.id = event_id) as likes
                FROM users as u
                  INNER JOIN events as e
                    ON u.id = e.user_id
                  INNER JOIN event_categories as ec
                    ON e.id = ec.event_id
                  INNER JOIN categories as c
                    ON c.id = ec.category_id
                  LEFT JOIN event_tags as et
                    ON e.id = et.event_id
                  LEFT JOIN tags as t
                    ON t.id = et.tag_id
                WHERE e.active = 1 and exists (SELECT COUNT(id) as total FROM photos WHERE e.id = event_id GROUP BY id)
                GROUP BY e.id, c.id, t.id;");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute("DROP VIEW IF EXISTS event_search;");
    }
}
