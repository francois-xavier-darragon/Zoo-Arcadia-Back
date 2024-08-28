-- MySQL dump 10.13  Distrib 8.0.37, for Linux (x86_64)
--
-- Host: localhost    Database: arcadia
-- ------------------------------------------------------
-- Server version	8.0.37-0ubuntu0.22.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `animal`
--

DROP TABLE IF EXISTS `animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `animal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `health` varchar(50) NOT NULL,
  `description` longtext,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `breed_id` int NOT NULL,
  `habitat_id` int NOT NULL,
  `enclosure_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6AAB231FA8B4A30F` (`breed_id`),
  KEY `IDX_6AAB231FAFFE2D26` (`habitat_id`),
  KEY `IDX_6AAB231FD04FE1E5` (`enclosure_id`),
  CONSTRAINT `FK_animal_breed` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`),
  CONSTRAINT `FK_animal_enclosure` FOREIGN KEY (`enclosure_id`) REFERENCES `enclosure` (`id`),
  CONSTRAINT `FK_animal_habitat` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animal`
--

LOCK TABLES `animal` WRITE;
/*!40000 ALTER TABLE `animal` DISABLE KEYS */;
INSERT INTO `animal` VALUES (1,'Simba','parfaite',NULL,'2024-07-24 08:28:40','2024-08-01 12:51:05',NULL,1,1,1),(2,'Sacs','En attente du rapport vétérinaire',NULL,'2024-07-26 12:43:31','2024-07-26 12:43:31',NULL,2,3,3),(3,'Rox','En attente du rapport vétérinaire',NULL,'2024-07-26 12:47:35','2024-07-26 12:47:35',NULL,3,2,4),(4,'Dumbo','En attente du rapport vétérinaire',NULL,'2024-07-26 12:49:03','2024-07-26 12:49:03',NULL,4,1,5),(5,'Taunt','En attente du rapport vétérinaire',NULL,'2024-07-26 12:50:01','2024-07-26 12:50:01',NULL,5,1,6),(6,'Nala','parfaite',NULL,'2024-08-05 07:21:03','2024-08-05 07:33:11',NULL,1,1,1);
/*!40000 ALTER TABLE `animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `breed`
--

DROP TABLE IF EXISTS `breed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `breed` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F8AF884F5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `breed`
--

LOCK TABLES `breed` WRITE;
/*!40000 ALTER TABLE `breed` DISABLE KEYS */;
INSERT INTO `breed` VALUES (1,'Lion','2024-07-24 08:28:40','2024-07-24 08:28:40',NULL),(2,'Caïman','2024-07-26 12:43:31','2024-07-26 12:43:31',NULL),(3,'Panda roux','2024-07-26 12:47:35','2024-07-26 12:47:35',NULL),(4,'Elephant','2024-07-26 12:49:03','2024-07-26 12:49:03',NULL),(5,'Zèbre','2024-07-26 12:50:01','2024-07-26 12:50:01',NULL);
/*!40000 ALTER TABLE `breed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enclosure`
--

DROP TABLE IF EXISTS `enclosure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enclosure` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext,
  `short_description` varchar(255) NOT NULL,
  `habitat_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E0F73063AFFE2D26` (`habitat_id`),
  CONSTRAINT `FK_enclosure_habitat` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enclosure`
--

LOCK TABLES `enclosure` WRITE;
/*!40000 ALTER TABLE `enclosure` DISABLE KEYS */;
INSERT INTO `enclosure` VALUES (1,'Enclos des lions','Des arbres clairsemés, comme les acacias, offrent des points d\'ombre et des perchoirs, tandis que des rochers stratégiquement disposés fournissent des lieux de repos et de guet pour les félins. Le sol est recouvert d\'herbes hautes, créant un terrain idéal pour la chasse et l\'exploration. Des zones sablonneuses permettent aux lions de s\'allonger confortablement et de se prélasser au soleil.\r\n\r\nDes barrières naturelles, telles que des collines artificielles et des fossés, sont intégrées pour assurer la sécurité tout en offrant un sentiment d\'ouverture. Des structures telles que des grottes et des abris en pierre imitent les refuges naturels, permettant aux lions de se retirer et de se cacher. Les visiteurs peuvent observer les lions depuis des plateformes surélevées ou des chemins sécurisés, offrant une vue plongeante sur l\'enclos sans déranger les animaux. Ce cadre recrée les interactions sociales et les comportements naturels des lions, offrant une expérience immersive tout en sensibilisant le public à la conservation de ces majestueux prédateurs.','L\'enclos des lions offre un espace naturel pour l\'exploration et la chasse.',1),(2,'Enclos des jaguars','sqqdqvd','sbsbd',2),(3,'Enclos des caïmans','L\'enclos des caïmans est un espace semi-aquatique recréant leur habitat naturel de marécage. Il comprend une grande étendue d\'eau stagnante avec des zones de végétation dense, des rochers pour se prélasser','L\'enclos des caïmans est un espace semi-aquatique recréant leur habitat naturel de marécage.',3),(4,'Enclos des pandas roux','L\'enclos des pandas roux est conçu pour reproduire fidèlement leur habitat naturel de montagne, offrant une forêt dense de bambous, d\'arbres et de buissons. De nombreux arbres à feuilles persistantes, tels que les pins et les chênes, sont stratégiquement plantés pour fournir des opportunités de grimper, un comportement essentiel pour ces animaux arboricoles. Les branches des arbres sont renforcées pour supporter le poids des pandas roux et sont disposées de manière à créer des parcours de grimpe stimulants et variés.\r\n\r\nDes abris naturels, tels que des cavités d\'arbres et des niches en bois, sont répartis dans l\'enclos pour permettre aux pandas roux de se reposer et de se cacher, mimant les crevasses et les nids qu\'ils utiliseraient dans la nature. Le sol est couvert de feuillage et de sous-bois épais pour offrir un environnement confortable et enrichissant.','L\'enclos de panda roux est constitué d\'une forêt de bambous et d\'arbres, offrant des branches pour grimper et des abris naturels pour se reposer',2),(5,'Enclos des éléphants','Cet espace fournit de l\'ombre et des sources de nourriture, il inclut plusieurs points d\'eau, comme des étangs et des mares, où les éléphants peuvent se baigner, boire et jouer, recréant leur comportement naturel de socialisation et de toilettage. Des terrains variés, allant de sols sablonneux à des zones herbeuses, permettent aux éléphants de marcher et de creuser.\r\n\r\nDes troncs d\'arbres abattus et des structures en bois sont disséminés pour permettre aux éléphants de se frotter, de se gratter et de démontrer leur force en les manipulant. Les barrières naturelles et les collines artificielles offrent une séparation subtile entre les éléphants et les visiteurs, qui peuvent les observer depuis des plateformes sécurisées et surélevées. L\'enclos est conçu pour promouvoir le bien-être des éléphants, en leur offrant un environnement enrichissant et stimulant qui encourage les comportements naturels et favorise la santé physique et mentale de ces majestueux animaux.','L\'enclos des éléphants offre des points d\'eau, permettant aux éléphants de se nourrir, se baigner et de se socialiser.',1),(6,'Enclos des zèbres','Les zèbres peuvent se déplacer librement sur un terrain légèrement vallonné, offrant une diversité de paysages et des zones d\'ombre pour se protéger du soleil.\r\n\r\nDes mares et des points d\'eau sont intégrés pour permettre aux zèbres de boire et de se rafraîchir. Des barrières naturelles, comme des fossés et des haies, sont utilisées pour délimiter l\'enclos tout en maintenant une esthétique naturelle. Des zones de terre battue permettent aux zèbres de se rouler, un comportement essentiel pour leur toilettage et leur confort. Les visiteurs peuvent observer les zèbres depuis des sentiers ou des plateformes surélevées, leur offrant une vue imprenable sur les animaux sans perturber leur environnement. Cet enclos vise à recréer les conditions naturelles de la savane, permettant aux zèbres d\'exprimer leurs comportements sociaux et naturels tout en sensibilisant le public à leur conservation.','L\'enclos des zèbres offre un espace ouvert pour la course et le pâturage.',1);
/*!40000 ALTER TABLE `enclosure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `habitat`
--

DROP TABLE IF EXISTS `habitat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `habitat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` longtext,
  `short_description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `habitat`
--

LOCK TABLES `habitat` WRITE;
/*!40000 ALTER TABLE `habitat` DISABLE KEYS */;
INSERT INTO `habitat` VALUES (1,'Savane','Cet espace est caractérisé par des prairies ondulantes parsemées de quelques arbres et arbustes, comme les acacias, qui offrent de l\'ombre et des points de repère visuels. Le sol est souvent sec et sablonneux, avec des zones boueuses en saison humide pour simuler les points d\'eau naturels.\r\n\r\nLa diversité des plantes est sélectionnée pour résister à la sécheresse et aux feux contrôlés, favorisant un écosystème équilibré. Les animaux, tels que les zèbres, girafes, éléphants, et antilopes, y cohabitent de manière à refléter les interactions complexes et les comportements sociaux observés dans la nature, tandis que les prédateurs que les lions vivent à part.\r\n\r\nDes structures comme des termitières artificielles et des rochers ajoutent à l\'authenticité de l\'environnement.\r\n\r\nDes mesures de conservation et de gestion durable sont mises en place pour assurer le bien-être des animaux et la pérennité de cet écosystème. Les visiteurs peuvent observer les animaux depuis des plateformes surélevées ou des véhicules de safari, leur permettant de découvrir la richesse et la dynamique de la savane tout en respectant l\'espace des animaux. Ce cadre éducatif et récréatif vise à sensibiliser le public à la beauté et à la fragilité des écosystèmes de savane, tout en contribuant à la conservation des espèces menacées.','L\'habitat savane est une vaste étendue herbeuse avec quelques arbres clairsemés, recréant les conditions naturelles pour abriter des espèces telles que les lions, zèbres et éléphants.','2024-07-24 08:13:19','2024-07-24 08:13:19',NULL),(2,'Forêt tropical','L\'habitat de la forêt tropicale est un écosystème luxuriant et diversifié, caractérisé par une végétation dense et une canopée épaisse qui crée un environnement ombragé en dessous. Ce type d\'habitat est dominé par des arbres à feuilles persistantes qui atteignent des hauteurs impressionnantes, formant plusieurs couches de végétation.\r\n\r\nLa canopée supérieure abrite une grande variété d\'espèces d\'oiseaux, d\'insectes et de petits mammifères, comme les singes et les écureuils volants. Les plantes épiphytes, telles que les orchidées et les broméliacées, poussent sur les branches des arbres, ajoutant une dimension verticale à la biodiversité de la forêt.\r\n\r\nAu niveau du sol, le sous-bois est rempli de fougères, de palmiers et de jeunes arbres en croissance. La lumière du soleil atteint rarement le sol de la forêt, créant un environnement humide et frais, propice à une diversité d\'organismes tels que les amphibiens, les reptiles et une variété d\'insectes. Les grands mammifères, comme les jaguars, les tapirs et les éléphants de forêt, y trouvent abri et nourriture.\r\n\r\nLes mesures de conservation et de gestion durable sont essentielles pour protéger la biodiversité de la forêt tropicale et garantir la survie des espèces menacées. Les visiteurs peuvent explorer la forêt à travers des sentiers balisés ou des passerelles suspendues, leur permettant de découvrir la richesse et la beauté de cet écosystème tout en minimisant leur impact sur l\'environnement.','L\'habitat tropical recréant une forêt dense et luxuriante, caractérisée par un climat chaud et humide toute l\'année, de nombreuses plantes exotiques et une faune variée, incluant des oiseaux colorés, des singes, et autres primates','2024-07-24 08:15:34','2024-07-24 08:15:34',NULL),(3,'Marécage','L\'habitat  marécage est un écosystème humide et riche en biodiversité, caractérisé par une végétation dense et une abondance d\'eau stagnante ou en mouvement lent. Les marécages se trouvent généralement dans les zones basses et mal drainées, où le sol est saturé en eau pendant une grande partie de l\'année.\r\n\r\nL\'habitat  marécage abrite une variété d\'animaux adaptés à des conditions humides. Les amphibiens, comme les grenouilles et les salamandres, prospèrent dans cet environnement, utilisant l\'eau pour se reproduire et les zones humides pour se nourrir. Les reptiles, tels que les tortues et les alligators, sont également courants, trouvant refuge et nourriture dans les marais.\r\n\r\nLes mammifères, comme les castors, les ratons laveurs et les loutres, exploitent les ressources abondantes des marécages pour se nourrir et construire leurs abris. Les castors, en particulier, jouent un rôle écologique clé en modifiant le paysage avec leurs barrages, créant ainsi des habitats pour d\'autres espèces.\r\n\r\nLes marécages remplissent des fonctions écologiques essentielles, notamment la purification de l\'eau, le contrôle des inondations et la séquestration du carbone. Ils agissent comme des éponges naturelles, absorbant l\'excès d\'eau lors des périodes de fortes pluies et réduisant ainsi les risques d\'inondation en aval.\r\n\r\nLes mesures de conservation et de gestion durable sont cruciales pour préserver les marécages et les services écosystémiques qu\'ils fournissent. Les visiteurs peuvent explorer les marécages via des sentiers surélevés ou en canoë, ce qui leur permet d\'observer de près la diversité des espèces et les interactions écologiques sans perturber l\'habitat fragile. Ces infrastructures permettent également une visite en toute sécurité, offrant une expérience enrichissante tout en minimisant les risques.','L\'habitat marécage est une zone humide avec des plantes aquatiques, abritant des oiseaux, reptiles et amphibiens.','2024-07-24 08:15:53','2024-07-24 08:15:53',NULL);
/*!40000 ALTER TABLE `habitat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `size` int DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `animal_id` int DEFAULT NULL,
  `habitat_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `enclosure_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045F8E962C16` (`animal_id`),
  KEY `IDX_C53D045FAFFE2D26` (`habitat_id`),
  KEY `IDX_C53D045FED5CA9E6` (`service_id`),
  KEY `IDX_C53D045FD04FE1E5` (`enclosure_id`),
  CONSTRAINT `FK_C53D045FD04FE1E5` FOREIGN KEY (`enclosure_id`) REFERENCES `enclosure` (`id`),
  CONSTRAINT `FK_image_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  CONSTRAINT `FK_image_habitat` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`),
  CONSTRAINT `FK_image_service` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
INSERT INTO `image` VALUES (3,'foret-tropical-66a0b826315e6325045385.jpg','foret-tropical.jpg',224822,'image/jpeg','2024-07-24 08:15:34','2024-07-24 08:15:34',NULL,2,NULL,NULL),(4,'marecage-66a0b839bd27c921110822.jpg','marecage.jpg',193079,'image/jpeg','2024-07-24 08:15:53','2024-07-24 08:15:53',NULL,3,NULL,NULL),(5,'lion-500x750-66a0bb38ec316593006673.jpg','lion 500x750.jpg',70123,'image/jpeg','2024-07-24 08:28:40','2024-07-24 08:28:40',1,NULL,NULL,NULL),(8,'portrait-young-businessman-with-mustache-glasses-3d-rendering-1-66a1deb97faea749847864.jpg','portrait-young-businessman-with-mustache-glasses-3d-rendering (1).jpg',153955,'image/jpeg','2024-07-25 05:12:25','2024-07-25 05:12:25',NULL,NULL,NULL,NULL),(76,'savane-750x500-66a38e13edb1c314963776.jpg','savane 750x500.jpg',138401,'image/jpeg','2024-07-26 11:52:51','2024-07-26 11:52:51',NULL,1,NULL,NULL),(84,'lion-v3-66a38f67963df403742430.jpg','lion-v3.jpg',61238,'image/jpeg','2024-07-26 11:58:31','2024-07-26 11:58:31',1,NULL,NULL,NULL),(87,'caiman-1-66a399f9da089567703840.jpg','caiman (1).jpg',104032,'image/jpeg','2024-07-26 12:43:37','2024-07-26 12:43:37',2,NULL,NULL,NULL),(88,'panda-roux-66a39af753c27601529226.jpg','panda-roux.jpg',128691,'image/jpeg','2024-07-26 12:47:51','2024-07-26 12:47:51',3,NULL,NULL,NULL),(89,'elephant-66a39b3f4dca8352210543.jpg','elephant.jpg',137951,'image/jpeg','2024-07-26 12:49:03','2024-07-26 12:49:03',4,NULL,NULL,NULL),(90,'zebras-4258909-1280-66a39b7989cfa152462236.jpg','zebras-4258909_1280.jpg',156656,'image/jpeg','2024-07-26 12:50:01','2024-07-26 12:50:01',5,NULL,NULL,NULL),(91,'portrait-young-businessman-with-mustache-glasses-3d-rendering-66aa54349063b136765774.jpg','portrait-young-businessman-with-mustache-glasses-3d-rendering.jpg',153955,'image/jpeg','2024-07-31 15:11:48','2024-07-31 15:11:48',NULL,NULL,NULL,NULL),(92,'portrait-young-businessman-with-mustache-glasses-3d-rendering-66ab6f43a42f2216523897.jpg','portrait-young-businessman-with-mustache-glasses-3d-rendering.jpg',153955,'image/jpeg','2024-08-01 11:19:31','2024-08-01 11:19:31',NULL,NULL,NULL,NULL),(93,'restaurant-66ac715005a77977326646.jpg','restaurant.jpg',81396,'image/jpeg','2024-08-02 05:40:31','2024-08-02 05:40:31',NULL,NULL,2,NULL),(94,'bouitique-66ac950c3fed1320301612.jpg','bouitique.jpg',234615,'image/jpeg','2024-08-02 08:13:00','2024-08-02 08:13:00',NULL,NULL,3,NULL),(95,'visite-guide-66ac97f9a47bf016728655.jpg','visite guide.jpg',421190,'image/jpeg','2024-08-02 08:25:29','2024-08-02 08:25:29',NULL,NULL,4,NULL),(96,'reproduction-66ac9d257c659464606088.jpg','reproduction.jpg',91067,'image/jpeg','2024-08-02 08:47:33','2024-08-02 08:47:33',NULL,NULL,5,NULL),(97,'lionne-66b07d5fe3438491281136.jpg','lionne.jpg',101521,'image/jpeg','2024-08-05 07:21:03','2024-08-05 07:21:03',6,NULL,NULL,NULL);
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notice`
--

DROP TABLE IF EXISTS `notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nickname` varchar(50) NOT NULL,
  `comment` varchar(50) NOT NULL,
  `status` int NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_480D45C2A76ED395` (`user_id`),
  CONSTRAINT `FK_notice_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notice`
--

LOCK TABLES `notice` WRITE;
/*!40000 ALTER TABLE `notice` DISABLE KEYS */;
INSERT INTO `notice` VALUES (1,'TEST','sb<sbs',1,'2024-07-26 10:21:56','2024-07-26 10:21:56',NULL,2),(2,'wbbwb','sb<sbs',1,'2024-07-29 06:18:12','2024-07-29 06:18:12',NULL,2),(3,'wbbwb','sb<sbsVQDVVQDV',1,'2024-07-29 06:18:20','2024-07-29 06:18:20',NULL,2),(4,'TEST','sb<sbsVQDVVQDV',1,'2024-07-29 06:18:28','2024-07-29 06:18:28',NULL,2),(6,'wbbwb','ch,c,h',0,'2024-08-02 11:57:03','2024-08-02 11:57:03',NULL,2),(7,'Visteur','<sbsdsbsdb',0,'2024-08-02 11:57:35','2024-08-02 11:57:35',NULL,3);
/*!40000 ALTER TABLE `notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `short_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (2,'Restaurant','Bienvenue au restaurant :  \"La Savane Gourmande\", votre oasis culinaire au cœur du zoo! Offrant une expérience gastronomique unique, notre restaurant vous invite à déguster une variété de plats inspirés des cuisines du monde, tout en profitant d\'une vue imprenable sur les habitats des animaux exotiques. Que vous souhaitiez savourer un repas copieux après une journée d\'aventure ou simplement prendre une pause avec une collation rafraîchissante, \"La Savane Gourmande\" est l\'endroit idéal pour petits et grands. Rejoignez-nous pour une escapade savoureuse en pleine nature!','2024-07-24 09:24:50','2024-07-24 09:24:50',NULL,'le restaurant la Savane Gourmande !'),(3,'Boutique','La boutique incontournable du zoo ! Plongez dans un univers fascinant où la beauté de la nature et l\'aventure s\'entrelacent. Ici, vous trouverez une sélection unique de souvenirs, jouets éducatifs, et articles artisanaux inspirés par la faune et la flore. Chaque objet raconte une histoire, vous invitant à emporter un morceau de la magie du zoo chez vous. Que vous soyez passionné par les animaux, un amoureux de la nature, ou en quête d\'un cadeau original, notre boutique saura éveiller votre curiosité et enrichir votre visite.','2024-07-24 09:28:13','2024-07-24 09:28:13',NULL,'La boutique incontournable du zoo !'),(4,'Visite guidée','Rejoignez-nous pour une visite guidée captivante du zoo! Découvrez des animaux fascinants, apprenez des faits étonnants et explorez les habitats recréés avec soin. Accompagné par un guide expert, plongez dans les secrets de la faune mondiale et vivez une expérience enrichissante et éducative. Parfait pour les familles, les amateurs de nature et les curieux de tout âge.','2024-08-02 08:25:29','2024-08-02 08:25:29',NULL,'La visite guidée !'),(5,'Programmes de reproduction','Découvrez nos programmes de reproduction en captivité, conçus pour protéger les espèces menacées et préserver la biodiversité. Nos experts travaillent sans relâche pour assurer des conditions optimales, favorisant la santé et le bien-être des animaux. Grâce à ces efforts, nous contribuons à la survie de nombreuses espèces pour les générations futures. Rejoignez-nous pour en apprendre davantage sur ces initiatives cruciales et leur impact positif sur la conservation de la faune.','2024-08-02 08:47:33','2024-08-02 08:47:33',NULL,'Programmes de reproduction en captivité');
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` int DEFAULT NULL,
  `address` json DEFAULT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `avatar_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`),
  UNIQUE KEY `UNIQ_8D93D64986383B10` (`avatar_id`),
  CONSTRAINT `FK_user_avatar` FOREIGN KEY (`avatar_id`) REFERENCES `image` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'fxd@gmail.com','darragon','darragon','[\"ROLE_ADMIN\"]','$2y$13$T03dnXDYdj.9.QvLyIGJC.Pw4N5nmBfRrqbbAt6C81UUie9ZCYYsO',667879458,'{\"zip\": \"15130\", \"city\": \"YTRAC\", \"street\": \"Rue du Puy de Peyre Arse\", \"complement\": null}',NULL,'2024-07-25 05:12:25','2024-07-25 05:12:25',NULL,8),(3,'fxdvisiteur@gmail.com','dd','dd','[\"ROLE_VISITOR\"]','$2y$13$TeC79G/YmYR678YJinaQ3u9Frz8oFHNUI5exHkykWyNzq9jfcfLse',NULL,NULL,NULL,'2024-07-31 15:11:48','2024-07-31 15:11:48',NULL,91),(4,'fxdveto@gmail.com','fx','dd','[\"ROLE_VETERINARY\"]','$2y$13$smpYSPUqa2ehucZEXMewxuU5BbKpWGNvq6EeMB/IozGhNBYf19A2i',NULL,'{\"zip\": null, \"city\": null, \"street\": null, \"complement\": null}',NULL,'2024-08-01 11:19:31','2024-08-01 11:19:31',NULL,92),(6,'fxd15130@gmail.com','fx','dd','[\"ROLE_ADMIN\"]','$2y$13$BPPGcNysCgO.ErDuXTnE9eK2AaTvgOB.iIy1fyayB4N0Px6h3ZR3C',NULL,'{\"zip\": null, \"city\": null, \"street\": null, \"complement\": null}',NULL,'2024-08-02 12:54:16','2024-08-02 12:54:16',NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `veterinary_report`
--

DROP TABLE IF EXISTS `veterinary_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `veterinary_report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `detail` longtext,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `user_id` int DEFAULT NULL,
  `animal_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_53C7E56BA76ED395` (`user_id`),
  KEY `IDX_53C7E56B8E962C16` (`animal_id`),
  CONSTRAINT `FK_veterinary_report_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  CONSTRAINT `FK_veterinary_report_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `veterinary_report`
--

LOCK TABLES `veterinary_report` WRITE;
/*!40000 ALTER TABLE `veterinary_report` DISABLE KEYS */;
INSERT INTO `veterinary_report` VALUES (2,'Simba, le lion, est en parfaite santé selon l\'examen vétérinaire réalisé. Il présente une condition corporelle excellente avec une musculature bien développée et un pelage brillant sans signes d\'irritation ou de parasites. Son comportement est actif et alerte, et son appétit ainsi que sa consommation d\'eau sont normaux. Les examens cliniques montrent une respiration claire, des battements cardiaques réguliers, et aucune anomalie dans les systèmes musculo-squelettique, gastro-intestinal, urinaire ou reproducteur. Les analyses sanguines et les tests des selles et des urines sont tous dans les limites normales, confirmant l\'absence de maladies ou de troubles. Il est recommandé de maintenir une alimentation équilibrée, de promouvoir un exercice régulier et de prévoir des visites de routine tous les six mois pour le suivi général et les vaccinations préventives. Simba est en excellente forme physique et mentale.','2024-08-01 11:41:05','2024-08-01 11:41:05',NULL,NULL,1),(3,'Nala la lionne est en parfaite santé selon l\'examen vétérinaire réalisé. Il présente une condition corporelle excellente avec une musculature bien développée et un pelage brillant sans signes d\'irritation ou de parasites. Son comportement est actif et alerte, et son appétit ainsi que sa consommation d\'eau sont normaux. Les examens cliniques montrent une respiration claire, des battements cardiaques réguliers, et aucune anomalie dans les systèmes musculo-squelettique, gastro-intestinal, urinaire ou reproducteur. Les analyses sanguines et les tests des selles et des urines sont tous dans les limites normales, confirmant l\'absence de maladies ou de troubles. Il est recommandé de maintenir une alimentation équilibrée, de promouvoir un exercice régulier et de prévoir des visites de routine tous les six mois pour le suivi général et les vaccinations préventives. Nala est en excellente forme physique et mentale.','2024-08-05 07:33:11','2024-08-05 07:33:11',NULL,NULL,6);
/*!40000 ALTER TABLE `veterinary_report` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-28 13:26:15
