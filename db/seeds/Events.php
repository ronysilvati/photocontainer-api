<?php

use Phinx\Seed\AbstractSeed;

class Events extends AbstractSeed
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
        $this->execute("
        SET FOREIGN_KEY_CHECKS=0;
        
        INSERT INTO `events` (`id`, `user_id`, `groom`, `bride`, `eventdate`, `title`, `description`, `terms`, `approval_general`, `approval_photographer`, `approval_bride`, `active`, `created_at`, `updated_at`) VALUES
        (1, 1, 'Rick', 'Michone', '2017-04-21 00:00:00', 'Michone e Rick', 'Descrição.', 1, 1, 1, 1, 1, '2017-03-25 21:53:46', '2017-03-25 21:53:46'),
        (2, 1, 'Abraham', 'Rosita', '2017-04-21 00:00:00', 'Abraham e Rosita', 'Descrição.', 1, 1, 1, 1, 1, '2017-03-25 21:55:59', '2017-03-25 21:53:52');

        INSERT INTO `event_categories` (`id`, `event_id`, `category_id`, `created_at`, `updated_at`) VALUES
        (1, 2, 2, '2017-03-25 21:53:46', '2017-03-25 21:53:46'),
        (2, 2, 3, '2017-03-25 21:53:52', '2017-03-25 21:53:52');

        INSERT INTO `event_tags` (`id`, `event_id`, `tag_id`, `created_at`, `updated_at`) VALUES
        (1, 1, 70, '2017-03-25 21:53:47', '2017-03-25 21:53:47'),
        (2, 1, 2, '2017-03-25 21:53:47', '2017-03-25 21:53:47'),
        (3, 2, 70, '2017-03-25 21:53:53', '2017-03-25 21:53:53'),
        (4, 2, 2, '2017-03-25 21:53:53', '2017-03-25 21:53:53');
        
        SET FOREIGN_KEY_CHECKS=1;
        "
        );
    }
}
