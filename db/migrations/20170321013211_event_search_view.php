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

        $this->execute("
            DROP VIEW IF EXISTS event_search_publisher_download;
                    CREATE VIEW event_search_publisher_download AS
            SELECT d.id, d.photo_id, d.user_id, p.event_id, p.filename, e.title, photographer.name, t.id as tag_id
            FROM
               downloads as d
               INNER JOIN photos as p
                   ON d.photo_id = p.id
               INNER JOIN events as e
                     ON e.id = p.event_id
                INNER JOIN users as photographer
                    ON photographer.id = e.user_id
               INNER JOIN event_categories as ec
                 ON e.id = ec.event_id	    
               INNER JOIN categories as c
                 ON c.id = ec.category_id
               LEFT JOIN event_tags as et
                 ON e.id = et.event_id
               LEFT JOIN tags as t
                 ON t.id = et.tag_id
            GROUP BY d.user_id, d.photo_id, t.id
            ORDER BY d.created_at DESC;
        ");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute("DROP VIEW IF EXISTS event_search;");
        $this->execute("DROP VIEW IF EXISTS event_search_publisher;");
        $this->execute("DROP VIEW IF EXISTS event_search_publisher_download;");
    }
}
