INSERT INTO `animal` (`id`, `name`, `health`, `description`, `created_at`, `updated_at`, `deleted_at`, `breed_id`, `habitat_id`, `enclosure_id`) VALUES
('1', 'Simba', 'En attente du rapport vétérinaire', NULL, '2024-07-24 08:28:40', '2024-07-24 08:28:40', NULL, '1', '1', '1'),
('2', 'Sacs', 'En attente du rapport vétérinaire', NULL, '2024-07-26 12:43:31', '2024-07-26 12:43:31', NULL, '2', '3', '3'),
('3', 'Rox', 'En attente du rapport vétérinaire', NULL, '2024-07-26 12:47:35', '2024-07-26 12:47:35', NULL, '3', '2', '4'),
('4', 'Dumbo', 'En attente du rapport vétérinaire', NULL, '2024-07-26 12:49:03', '2024-07-26 12:49:03', NULL, '4', '1', '5'),
('5', 'Taunt', 'En attente du rapport vétérinaire', NULL, '2024-07-26 12:50:01', '2024-07-26 12:50:01', NULL, '5', '1', '6');

INSERT INTO `breed` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'Lion', '2024-07-24 08:28:40', '2024-07-24 08:28:40', NULL),
('2', 'Caïman', '2024-07-26 12:43:31', '2024-07-26 12:43:31', NULL),
('3', 'Panda roux', '2024-07-26 12:47:35', '2024-07-26 12:47:35', NULL),
('4', 'Elephant', '2024-07-26 12:49:03', '2024-07-26 12:49:03', NULL),
('5', 'Zèbre', '2024-07-26 12:50:01', '2024-07-26 12:50:01', NULL);

INSERT INTO `enclosure` (`id`, `name`, `description`, `habitat_id`) VALUES
('1', 'Enclos des lions', 'qfsfsqfsf', '1'),
('2', 'Enclos des jaguars', 'sqqdqvd', '2'),
('3', 'Enclos des caïmans', 'sdvsdvdvdv', '3'),
('4', 'Enclos des pandas roux', 'dfbwsdfbsb', '2'),
('5', 'Enclos des éléphants', 'q<vvqdvq', '1'),
('6', 'Enclos des zèbres', 'dwbfsbwsfb', '1');

INSERT INTO `habitat` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'Savane', 'qfqssffsf', '2024-07-24 08:13:19', '2024-07-24 08:13:19', NULL),
('2', 'Forêt tropical', 'qsfsfqs', '2024-07-24 08:15:34', '2024-07-24 08:15:34', NULL),
('3', 'Marécage', 'sdvsdvdvssv', '2024-07-24 08:15:53', '2024-07-24 08:15:53', NULL);

INSERT INTO `image` (`id`, `name`, `original_name`, `size`, `mime_type`, `created_at`, `updated_at`, `animal_id`, `habitat_id`, `service_id`) VALUES
('3', 'foret-tropical-66a0b826315e6325045385.jpg', 'foret-tropical.jpg', '224822', 'image/jpeg', '2024-07-24 08:15:34', '2024-07-24 08:15:34', NULL, '2', NULL),
('4', 'marecage-66a0b839bd27c921110822.jpg', 'marecage.jpg', '193079', 'image/jpeg', '2024-07-24 08:15:53', '2024-07-24 08:15:53', NULL, '3', NULL),
('5', 'lion-500x750-66a0bb38ec316593006673.jpg', 'lion 500x750.jpg', '70123', 'image/jpeg', '2024-07-24 08:28:40', '2024-07-24 08:28:40', '1', NULL, NULL),
('6', 'boutique-1-66a0c916ce833523079984.jpg', 'boutique (1).jpg', '127980', 'image/jpeg', '2024-07-24 09:27:50', '2024-07-24 09:27:50', NULL, NULL, '2'),
('8', 'portrait-young-businessman-with-mustache-glasses-3d-rendering-1-66a1deb97faea749847864.jpg', 'portrait-young-businessman-with-mustache-glasses-3d-rendering (1).jpg', '153955', 'image/jpeg', '2024-07-25 05:12:25', '2024-07-25 05:12:25', NULL, NULL, NULL),
('68', 'boutique-de-jouet-1-66a259ee58373081479456-66a3799a6a4d3448026256.jpg', 'boutique-de-jouet-1-66a259ee58373081479456.jpg', '112547', 'image/jpeg', '2024-07-26 10:25:30', '2024-07-26 10:25:30', NULL, NULL, '3'),
('76', 'savane-750x500-66a38e13edb1c314963776.jpg', 'savane 750x500.jpg', '138401', 'image/jpeg', '2024-07-26 11:52:51', '2024-07-26 11:52:51', NULL, '1', NULL),
('84', 'lion-v3-66a38f67963df403742430.jpg', 'lion-v3.jpg', '61238', 'image/jpeg', '2024-07-26 11:58:31', '2024-07-26 11:58:31', '1', NULL, NULL),
('87', 'caiman-1-66a399f9da089567703840.jpg', 'caiman (1).jpg', '104032', 'image/jpeg', '2024-07-26 12:43:37', '2024-07-26 12:43:37', '2', NULL, NULL),
('88', 'panda-roux-66a39af753c27601529226.jpg', 'panda-roux.jpg', '128691', 'image/jpeg', '2024-07-26 12:47:51', '2024-07-26 12:47:51', '3', NULL, NULL),
('89', 'elephant-66a39b3f4dca8352210543.jpg', 'elephant.jpg', '137951', 'image/jpeg', '2024-07-26 12:49:03', '2024-07-26 12:49:03', '4', NULL, NULL),
('90', 'zebras-4258909-1280-66a39b7989cfa152462236.jpg', 'zebras-4258909_1280.jpg', '156656', 'image/jpeg', '2024-07-26 12:50:01', '2024-07-26 12:50:01', '5', NULL, NULL);

INSERT INTO `notice` (`id`, `nickname`, `comment`, `status`, `created_at`, `updated_at`, `deleted_at`, `user_id`) VALUES
('1', 'TEST', 'sb<sbs', '0', '2024-07-26 10:21:56', '2024-07-26 10:21:56', NULL, '2');

INSERT INTO `service` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
('2', 'Restaurant', 'dqqqsvsv', '2024-07-24 09:24:50', '2024-07-24 09:24:50', NULL),
('3', 'Boutique', 'fsdbsfbfd', '2024-07-24 09:28:13', '2024-07-24 09:28:13', NULL);

INSERT INTO `user` (`id`, `email`, `first_name`, `last_name`, `roles`, `password`, `phone`, `address`, `api_token`, `created_at`, `updated_at`, `deleted_at`, `avatar_id`) VALUES
('2', 'fxd@gmail.com', 'darragon', 'darragon', '[\"ROLE_ADMIN\"]', '$2y$13$T03dnXDYdj.9.QvLyIGJC.Pw4N5nmBfRrqbbAt6C81UUie9ZCYYsO', '667879458', '{\"zip\": \"15130\", \"city\": \"YTRAC\", \"street\": \"Rue du Puy de Peyre Arse\", \"complement\": null}', NULL, '2024-07-25 05:12:25', '2024-07-25 05:12:25', NULL, '8');

