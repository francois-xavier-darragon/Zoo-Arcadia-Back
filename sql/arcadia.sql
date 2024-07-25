INSERT INTO `animal` (`id`, `name`, `health`, `description`, `created_at`, `updated_at`, `deleted_at`, `breed_id`, `habitat_id`, `enclosure_id`) VALUES
('1', 'Simba', 'En attente du rapport vétérinaire', NULL, '2024-07-24 08:28:40', '2024-07-24 08:28:40', NULL, '1', '1', '1');

INSERT INTO `breed` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'Lion', '2024-07-24 08:28:40', '2024-07-24 08:28:40', NULL);

INSERT INTO `enclosure` (`id`, `name`, `description`, `habitat_id`) VALUES
('1', 'Enclos des lions', 'qfsfsqfsf', '1'),
('2', 'Enclos des jaguars', 'sqqdqvd', '2'),
('3', 'Enclos des caïmans', 'sdvsdvdvdv', '3');

INSERT INTO `habitat` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'Savane', 'qfqssffsf', '2024-07-24 08:13:19', '2024-07-24 08:13:19', NULL),
('2', 'Forêt tropical', 'qsfsfqs', '2024-07-24 08:15:34', '2024-07-24 08:15:34', NULL),
('3', 'Marécage', 'sdvsdvdvssv', '2024-07-24 08:15:53', '2024-07-24 08:15:53', NULL);

INSERT INTO `image` (`id`, `name`, `original_name`, `size`, `mime_type`, `created_at`, `updated_at`, `animal_id`, `habitat_id`, `service_id`) VALUES
('1', 'avatar-159236-640-66a0b7680c518998943783.png', 'avatar-159236_640.png', '27424', 'image/png', '2024-07-24 08:12:23', '2024-07-24 08:12:23', NULL, NULL, NULL),
('3', 'foret-tropical-66a0b826315e6325045385.jpg', 'foret-tropical.jpg', '224822', 'image/jpeg', '2024-07-24 08:15:34', '2024-07-24 08:15:34', NULL, '2', NULL),
('4', 'marecage-66a0b839bd27c921110822.jpg', 'marecage.jpg', '193079', 'image/jpeg', '2024-07-24 08:15:53', '2024-07-24 08:15:53', NULL, '3', NULL),
('5', 'lion-500x750-66a0bb38ec316593006673.jpg', 'lion 500x750.jpg', '70123', 'image/jpeg', '2024-07-24 08:28:40', '2024-07-24 08:28:40', '1', NULL, NULL),
('6', 'boutique-1-66a0c916ce833523079984.jpg', 'boutique (1).jpg', '127980', 'image/jpeg', '2024-07-24 09:27:50', '2024-07-24 09:27:50', NULL, NULL, '2'),
('7', 'boutique-1-66a0c92d8ac99560734489.jpg', 'boutique (1).jpg', '127980', 'image/jpeg', '2024-07-24 09:28:13', '2024-07-24 09:28:13', NULL, NULL, '3'),
('8', 'portrait-young-businessman-with-mustache-glasses-3d-rendering-1-66a1deb97faea749847864.jpg', 'portrait-young-businessman-with-mustache-glasses-3d-rendering (1).jpg', '153955', 'image/jpeg', '2024-07-25 05:12:25', '2024-07-25 05:12:25', NULL, NULL, NULL),
('9', 'savane-66a1df71162c7479712380.jpg', 'Savane.jpg', '138401', 'image/jpeg', '2024-07-25 05:15:29', '2024-07-25 05:15:29', NULL, '1', NULL);

INSERT INTO `service` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
('2', 'Restaurant', 'dqqqsvsv', '2024-07-24 09:24:50', '2024-07-24 09:24:50', NULL),
('3', 'Boutique', 'fsdbsfbfd', '2024-07-24 09:28:13', '2024-07-24 09:28:13', NULL);

INSERT INTO `user` (`id`, `email`, `first_name`, `last_name`, `roles`, `password`, `phone`, `address`, `api_token`, `created_at`, `updated_at`, `deleted_at`, `avatar_id`) VALUES
('2', 'fxd@gmail.com', 'darragon', 'darragon', '[\"ROLE_ADMIN\"]', '$2y$13$T03dnXDYdj.9.QvLyIGJC.Pw4N5nmBfRrqbbAt6C81UUie9ZCYYsO', '667879458', '{\"zip\": \"15130\", \"city\": \"YTRAC\", \"street\": \"Rue du Puy de Peyre Arse\", \"complement\": null}', NULL, '2024-07-25 05:12:25', '2024-07-25 05:12:25', NULL, '8');

