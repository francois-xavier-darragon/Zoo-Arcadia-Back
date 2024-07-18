INSERT INTO `animal` VALUES
('1', '1', '1', '1', 'Simba', 'parfaite', '2024-07-17 11:08:07', '2024-07-17 11:08:07', NULL);

INSERT INTO `breed` VALUES
('1', 'Lion', '2024-07-17 11:08:07', '2024-07-17 11:08:07', NULL);

INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20240716061314', '2024-07-17 09:28:26', '10551'),
('DoctrineMigrations\\Version20240717113644', '2024-07-17 11:36:49', '15');

INSERT INTO `enclosure` VALUES
('1', '1', 'Enclos des lions', 'ddvdvdsv'),
('2', '2', 'Enclos des caïmans', 'dsbsb'),
('3', '3', 'Enclos des jaguars', 'vdsvds');

INSERT INTO `habitat` VALUES
('1', 'Savane', 'fbfbf', '2024-07-17 09:36:54', '2024-07-17 09:36:54', NULL),
('2', 'Marécage', 'dvsdvdsv', '2024-07-17 10:37:13', '2024-07-17 10:37:13', NULL),
('3', 'Tropical', 'ddvsvdv', '2024-07-17 10:38:34', '2024-07-17 10:38:34', NULL);

INSERT INTO `image` VALUES
('1', NULL, NULL, 'portrait-young-businessman-with-mustache-glasses-3d-rendering-1-6697903be383f130466077.jpg', 'portrait-young-businessman-with-mustache-glasses-3d-rendering (1).jpg', '153955', 'image/jpeg', '2024-07-17 09:34:51', '2024-07-17 09:34:51'),
('2', NULL, '1', 'savane-669790b6202c0432633604.jpg', 'Savane.jpg', '138401', 'image/jpeg', '2024-07-17 09:36:54', '2024-07-17 09:36:54'),
('3', NULL, '2', 'marecage-66979ed97ff84751919330.jpg', 'marecage.jpg', '193079', 'image/jpeg', '2024-07-17 10:37:13', '2024-07-17 10:37:13'),
('4', NULL, '3', 'foret-tropical-66979f2a48a5b913554164.jpg', 'foret-tropical.jpg', '224822', 'image/jpeg', '2024-07-17 10:38:34', '2024-07-17 10:38:34'),
('5', '1', NULL, 'lion-500x750-6697a617a53fd521742226.jpg', 'lion 500x750.jpg', '70123', 'image/jpeg', '2024-07-17 11:08:07', '2024-07-17 11:08:07'),
('6', NULL, NULL, 'portrait-young-businessman-with-mustache-glasses-3d-rendering-1-6697a77cd4f9e994210721.jpg', 'portrait-young-businessman-with-mustache-glasses-3d-rendering (1).jpg', '153955', 'image/jpeg', '2024-07-17 11:14:04', '2024-07-17 11:14:04');

INSERT INTO `notice` VALUES
('1', '2', 'test', 'sg', '0', '2024-07-17 11:15:22', '2024-07-17 11:15:22', NULL),
('2', '2', 'test', 'sg', '0', '2024-07-17 11:47:20', '2024-07-17 11:47:20', NULL),
('3', '1', 'test', 'sg', '1', '2024-07-17 12:36:29', '2024-07-17 12:36:29', NULL);

INSERT INTO `service` VALUES
('1', 'Restaurant', 'fbdsbbfbbfbfb', '2024-07-17 11:15:37', '2024-07-17 11:15:37', NULL);

INSERT INTO `user` VALUES
('1', '1', 'fxd15130@gmail.com', 'darragon', 'darragon', '[\"ROLE_ADMIN\"]', '$2y$13$nLslxNhY2ogM161tcygqAudXiNyzMu5tnSYshYR.jfuUXI2U2o7OS', '667879458', '{\"zip\": \"15130\", \"city\": \"YTRAC\", \"street\": \"Rue du Puy de Peyre Arse\", \"complement\": null}', NULL, '2024-07-17 09:34:51', '2024-07-17 09:34:51', NULL),
('2', '6', 'fxd@gmail.com', 'darragon', 'ff', '[\"ROLE_VETERINARY\"]', '$2y$13$4JhdGUfpEt.eQ.srYQFUmOtPOXN1E5TPJ6owAkmdH7WXrkUSEBAYe', '667879458', '{\"zip\": \"15130\", \"city\": \"YTRAC\", \"street\": \"Rue du Puy de Peyre Arse\", \"complement\": null}', NULL, '2024-07-17 11:14:04', '2024-07-17 11:14:04', NULL);

INSERT INTO `veterinary_report` VALUES
('1', NULL, '1', 'bsfbsfbsfsfbsfdbbssbsfsbbs', '2024-07-17 11:14:48', '2024-07-17 11:14:48', NULL);

