<?php

use Phinx\Migration\AbstractMigration;

class TagsData extends AbstractMigration
{
    public function change()
    {
        $this->tagCreation();
        $this->updateTag();
    }

    public function updateTag()
    {
        $data = [
            [
                'tag_category_id' => 9,
                'description' => 'Nenhuma',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tag_category_id' => 5,
                'description' => 'Outro',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tag_category_id' => 1,
                'description' => 'Outro',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $tags = $this->table('tags');
        $tags->insert($data)->save();

        $this->execute("UPDATE tags SET description = 'Nenhuma' WHERE id = 49");
    }

    public function tagCreation()
    {
        $sql = "
    INSERT INTO `tag_categories` (`id`, `description`, `created_at`, `updated_at`) VALUES
        (1, 'Cenário', '2017-03-23 17:38:25', NULL),
        (2, 'Horário', '2017-03-23 14:38:40', NULL),
        (3, 'Metereologia', '2017-03-23 17:38:51', NULL),
        (4, 'Estação', '2017-03-23 17:38:58', NULL),
        (5, 'Estilo', '2017-03-23 17:39:02', NULL),
        (6, 'Religião', '2017-03-23 17:39:08', NULL),
        (7, 'Tipo', '2017-03-23 17:39:25', NULL),
        (8, 'Festa', '2017-03-23 17:39:38', NULL),
        (9, 'Música', '2017-03-23 17:39:47', NULL),
        (10, 'Atração especial', '2017-03-23 17:39:54', NULL),
        (11, 'Sexualidade', '2017-03-23 17:40:05', NULL),
        (12, 'Cores', '2017-03-23 17:40:05', NULL);";
        $this->execute($sql);

        $sql = "
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (1, 1, 'Cidade', '2017-03-23 17:44:39', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (2, 1, 'Praia', '2017-03-23 17:45:12', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (3, 1, 'Campo', '2017-03-23 17:45:16', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (4, 1, 'Fazenda / Sítio', '2017-03-23 17:45:31', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (5, 1, 'Residência', '2017-03-23 17:45:35', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (6, 1, 'Clube', '2017-03-23 17:45:40', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (7, 1, 'Castelo', '2017-03-23 17:45:44', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (8, 1, 'Barco', '2017-03-23 17:45:46', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (9, 1, 'Destination Wedding BRASIL', '2017-03-23 17:45:55', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (10, 1, 'Destination Wedding EXTERIOR', '2017-03-23 17:46:02', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (11, 2, 'Matinal', '2017-03-23 17:46:16', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (12, 2, 'Vespertino', '2017-03-23 17:46:22', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (13, 2, 'Noturno', '2017-03-23 17:46:27', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (14, 3, 'Sol', '2017-03-23 17:46:27', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (15, 3, 'Nublado', '2017-03-23 17:46:46', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (16, 3, 'Chuva', '2017-03-23 17:46:50', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (17, 3, 'Neve', '2017-03-23 17:46:52', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (18, 4, 'Verão', '2017-03-23 17:47:13', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (19, 4, 'Outono', '2017-03-23 17:47:17', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (20, 4, 'Inverno', '2017-03-23 17:47:21', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (21, 4, 'Primavera', '2017-03-23 17:47:24', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (22, 5, 'Clássico', '2017-03-23 17:47:47', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (23, 5, 'Moderno', '2017-03-23 17:47:50', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (24, 5, 'Rústico', '2017-03-23 17:47:56', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (25, 5, 'Contemporâneo', '2017-03-23 17:48:06', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (26, 5, 'Faça-vc-mesmo', '2017-03-23 17:48:13', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (27, 5, 'Vintage', '2017-03-23 17:48:16', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (28, 5, 'Fantasia', '2017-03-23 17:48:18', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (29, 5, 'Praiano', '2017-03-23 17:48:28', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (30, 5, 'Country', '2017-03-23 17:48:32', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (31, 5, 'Navy', '2017-03-23 17:48:34', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (32, 5, 'Étnico', '2017-03-23 17:48:34', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (33, 6, 'Católica', '2017-03-23 17:48:34', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (34, 6, 'Judaica', '2017-03-23 17:48:58', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (35, 6, 'Muçulmana', '2017-03-23 17:49:03', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (36, 6, 'Budista', '2017-03-23 17:49:06', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (37, 6, 'Anglicana', '2017-03-23 17:49:12', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (38, 6, 'Prebiteriano', '2017-03-23 17:49:15', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (39, 6, 'Ortodoxa', '2017-03-23 17:49:29', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (40, 6, 'Evangélica', '2017-03-23 17:49:35', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (41, 6, 'Ecumênica', '2017-03-23 17:49:40', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (42, 6, 'Espírita', '2017-03-23 17:49:44', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (43, 6, 'Outra', '2017-03-23 17:49:44', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (44, 7, 'Completo', '2017-03-23 17:50:34', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (45, 7, 'Mini-Wedding', '2017-03-23 17:50:44', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (46, 7, 'Outro', '2017-03-23 17:50:46', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (47, 7, 'Religioso', '2017-03-23 17:50:57', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (48, 7, 'Civil', '2017-03-23 17:50:57', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (49, 8, 'Sem festa', '2017-03-23 17:51:24', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (50, 8, 'Coquetel (Sem dança)', '2017-03-23 17:51:33', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (51, 8, 'Balada', '2017-03-23 17:51:39', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (52, 9, 'DJ', '2017-03-23 17:51:47', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (53, 9, 'Banda (Musica ao vivo)', '2017-03-23 17:52:03', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (54, 10, 'Cantor / Cantora', '2017-03-23 17:52:50', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (55, 10, 'Banda', '2017-03-23 17:53:13', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (56, 10, 'Ator / Atriz', '2017-03-23 17:53:19', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (57, 10, 'Escola de Samba', '2017-03-23 17:53:28', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (58, 11, 'Heterosexual', '2017-03-23 17:54:16', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (59, 11, 'Homoafetivo', '2017-03-23 17:54:23', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (60, 12, 'Branco', '2017-03-23 17:54:23', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (61, 12, 'Preto', '2017-03-23 17:54:23', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (62, 12, 'Cinza', '2017-03-23 17:54:23', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (63, 12, 'Pele', '2017-03-23 18:15:01', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (64, 12, 'Milão', '2017-03-23 18:15:07', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (65, 12, 'Ouro', '2017-03-23 18:15:11', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (66, 12, 'Laranja', '2017-03-23 18:15:20', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (67, 12, 'Pera', '2017-03-23 18:15:23', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (68, 12, 'Esarlate', '2017-03-23 18:15:29', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (69, 12, 'Roxo', '2017-03-23 18:15:31', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (70, 12, 'Rosa', '2017-03-23 18:15:46', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (71, 12, 'Azul Hawkes', '2017-03-23 18:16:08', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (72, 12, 'Violeta', '2017-03-23 18:16:13', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (73, 12, 'Azul Bahama', '2017-03-23 18:16:30', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (74, 12, 'Azul Dodger', '2017-03-23 18:16:36', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (75, 12, 'Azul Vela', '2017-03-23 18:16:43', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (76, 12, 'Turquesa', '2017-03-23 18:16:48', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (77, 12, 'Verde hortelã', '2017-03-23 18:16:53', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (78, 12, 'Arlequim', '2017-03-23 18:16:58', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (79, 12, 'Verde musgo', '2017-03-23 18:17:09', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (80, 12, 'Castanho', '2017-03-23 18:17:12', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (81, 12, 'Prata', '2017-03-23 18:17:19', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (82, 12, 'Grama dourada', '2017-03-23 18:17:24', '2017-03-23 14:44:12');
            INSERT INTO `tags` (`id`, `tag_category_id`, `description`, `created_at`, `updated_at`) VALUES (83, 12, 'Linho', '2017-03-23 18:17:26', '2017-03-23 14:44:12');
        ";
        $this->execute($sql);

        $data = [
            [
                'tag_category_id' => 10,
                'description'     => 'Nenhuma',
                'created_at'      => date('Y-m-d H:i:s'),
            ],
        ];
        $profiles = $this->table('tags');

        $profiles->insert($data)->save();

        $this->execute('UPDATE tags SET description = \'Presbiteriano\' where id = 38;');
    }
}
