<?php

use Phinx\Migration\AbstractMigration;

class ViewsUpdate extends AbstractMigration
{
    public function up()
    {
        $this->execute("
            DROP VIEW IF EXISTS event_search;
            CREATE VIEW event_search AS
            SELECT e.id, 
                   u.id as user_id,
                   IF(ud.name_by = 'name', u.name, ud.studio_name) as name,
                   title,
                   date(eventdate) as eventdate,
                   c.id as category_id,
                   c.description as category,
                   concat(',',group_concat(t.id separator ','),',') AS all_tags,
                   (SELECT COUNT(id) as total FROM photos WHERE e.id = event_id) as photos,
                   (SELECT COUNT(pf.id) as total FROM photo_favorites pf INNER JOIN photos p on pf.photo_id = p.id  WHERE e.id = event_id) as likes
            FROM users as u
              INNER JOIN user_details as ud
                 ON ud.user_id = u.id
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
             GROUP BY e.id, c.id;
        ");

        $this->execute("
            DROP VIEW IF EXISTS event_search_publisher;
            CREATE VIEW event_search_publisher AS
            SELECT e.id,
                   u.id as user_id,
                   IF(ud.name_by = 'name', u.name, ud.studio_name) as name,
                   title,
                   date(eventdate) as eventdate,
                   c.id as category_id,
                   c.description as category,
                   concat(',',group_concat(t.id separator ','),',') AS all_tags,
                   (SELECT COUNT(id) as total FROM photos WHERE e.id = event_id) as photos,
                   (SELECT COUNT(id) as total FROM event_favorites WHERE e.id = event_id) as likes
            FROM users as u
               INNER JOIN user_details as ud
                 ON ud.user_id = u.id
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
            GROUP BY e.id, c.id;
        ");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute("DROP VIEW IF EXISTS event_search;");
        $this->execute("DROP VIEW IF EXISTS event_search_publisher;");
    }
}
