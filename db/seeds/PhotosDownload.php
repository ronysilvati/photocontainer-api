<?php

use Phinx\Seed\AbstractSeed;

class PhotosDownload extends AbstractSeed
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
        (3, 1, 'Luiz', 'Roberta', '2017-04-29 00:00:00', 'Luiz e Roberta', 'Descrição.', 1, 1, 1, 1, 1, '2017-03-25 21:53:46', '2017-03-25 21:53:46');

        INSERT INTO `event_categories` (`event_id`, `category_id`, `created_at`, `updated_at`) VALUES
        (3, 1, '2017-03-25 21:53:46', '2017-03-25 21:53:46');

        INSERT INTO `event_tags` (`event_id`, `tag_id`, `created_at`, `updated_at`) VALUES
        (3, 70, '2017-03-25 21:53:47', '2017-03-25 21:53:47'),
        (3, 2, '2017-03-25 21:53:47', '2017-03-25 21:53:47');
        
        INSERT INTO `photos` (`id`, `event_id`, `filename`, `created_at`, `updated_at`) VALUES
	    (1, 3, 'photo1.jpg', '2017-04-05 23:25:20', NULL),
	    (2, 3, 'photo2.jpg', '2017-04-06 20:30:12', '2017-04-06 20:30:12');

        INSERT INTO `downloads` (`photo_id`, `user_id`, `created_at`, `updated_at`) VALUES
	    (1, '50', '2017-04-05 23:25:20', NULL),
	    (2, '50', '2017-04-05 23:25:20', NULL),
	    (1, '51', '2017-04-06 20:30:12', NULL),
	    (1, '51', '2017-04-06 20:30:12', NULL),
	    (2, '51', '2017-04-06 20:30:12', NULL);
        
        SET FOREIGN_KEY_CHECKS=1;
        "
        );
    }
}
