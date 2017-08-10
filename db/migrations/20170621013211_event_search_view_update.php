<?php

use Phinx\Migration\AbstractMigration;

class EventSearchViewUpdate extends AbstractMigration
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
                   GROUP_CONCAT(t.id) as all_tags,
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
                   GROUP_CONCAT(t.id) as all_tags,
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

        $this->execute("
            DROP VIEW IF EXISTS event_search_publisher_download;
                    CREATE VIEW event_search_publisher_download AS
            SELECT d.id, d.photo_id, d.user_id, p.event_id, p.filename, e.title, photographer.name, t.id as tag_id,
            (SELECT count(*) FROM photo_favorites as pf where pf.photo_id = d.photo_id) as favorite
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

        $this->execute("
            DROP VIEW IF EXISTS event_search_publisher_favorites;
            CREATE VIEW event_search_publisher_favorites AS
            (SELECT pf.id, pf.photo_id, pf.user_id, p.event_id, p.filename, 
                    e.title, photographer.name, t.id as tag_id, pf.created_at, 1 as favorite,
                    IF((SELECT DISTINCT dr.user_id FROM download_requests dr WHERE dr.event_id = p.event_id AND dr.user_id = pf.user_id) > 0 , 1, 0) as authorized
            FROM
               photo_favorites as pf
               INNER JOIN photos as p
                 ON pf.photo_id = p.id
               INNER JOIN events as e
                 ON e.id = p.event_id
               INNER JOIN users as photographer
                 ON photographer.id = e.user_id
               LEFT JOIN event_tags as et
                 ON e.id = et.event_id
               LEFT JOIN tags as t
                 ON t.id = et.tag_id
            GROUP BY pf.user_id, pf.photo_id, t.id
            ORDER BY pf.created_at DESC);
        ");

        $this->execute("
            DROP VIEW IF EXISTS event_search_approvals;
            CREATE VIEW event_search_approvals AS
            SELECT de.id, event_id, u.id as publisher_id, u.name as publisher_name, e.user_id as photographer_id, e.title, de.created_at, de.visualized
            FROM events as e
              INNER JOIN download_requests as de
                ON e.id = de.event_id
              INNER JOIN users as u
                 ON u.id = de.user_id
            WHERE de.active = 1
            ORDER BY de.created_at;
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
