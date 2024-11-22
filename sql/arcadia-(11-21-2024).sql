
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `animal` WRITE;
/*!40000 ALTER TABLE `animal` DISABLE KEYS */;
INSERT INTO `animal` VALUES (1,'Simba','parfaite',NULL,'2024-07-24 08:28:40','2024-08-01 12:51:05',NULL,1,1,1);
INSERT INTO `animal` VALUES (2,'Sacs','parfaite',NULL,'2024-07-26 12:43:31','2024-09-10 07:42:59',NULL,2,3,3);
INSERT INTO `animal` VALUES (3,'Rox','En attente du rapport vétérinaire',NULL,'2024-07-26 12:47:35','2024-07-26 12:47:35',NULL,3,2,4);
INSERT INTO `animal` VALUES (4,'Dumbo','En attente du rapport vétérinaire',NULL,'2024-07-26 12:49:03','2024-07-26 12:49:03',NULL,4,1,5);
INSERT INTO `animal` VALUES (5,'Taunt','En attente du rapport vétérinaire',NULL,'2024-07-26 12:50:01','2024-07-26 12:50:01',NULL,5,1,6);
INSERT INTO `animal` VALUES (6,'test','En attente du rapport vétérinaire',NULL,'2024-09-10 08:16:47','2024-09-10 08:16:47','2024-09-10 08:17:04',1,1,1);
INSERT INTO `animal` VALUES (7,'Roxi','parfaite',NULL,'2024-10-01 06:50:07','2024-10-01 06:51:34',NULL,3,2,4);
/*!40000 ALTER TABLE `animal` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `breed` WRITE;
/*!40000 ALTER TABLE `breed` DISABLE KEYS */;
INSERT INTO `breed` VALUES (1,'Lion','2024-07-24 08:28:40','2024-07-24 08:28:40',NULL);
INSERT INTO `breed` VALUES (2,'Caïman','2024-07-26 12:43:31','2024-07-26 12:43:31',NULL);
INSERT INTO `breed` VALUES (3,'Panda roux','2024-07-26 12:47:35','2024-07-26 12:47:35',NULL);
INSERT INTO `breed` VALUES (4,'Elephant','2024-07-26 12:49:03','2024-07-26 12:49:03',NULL);
INSERT INTO `breed` VALUES (5,'Zèbre','2024-07-26 12:50:01','2024-07-26 12:50:01',NULL);
/*!40000 ALTER TABLE `breed` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20241121151938','2024-11-21 15:19:49',2631);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `enclosure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enclosure` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext,
  `short_description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `habitat_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E0F73063AFFE2D26` (`habitat_id`),
  CONSTRAINT `FK_enclosure_habitat` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `enclosure` WRITE;
/*!40000 ALTER TABLE `enclosure` DISABLE KEYS */;
INSERT INTO `enclosure` VALUES (1,'Enclos des lions','Des arbres clairsemés, comme les acacias, offrent des points d\'ombre et des perchoirs, tandis que des rochers stratégiquement disposés fournissent des lieux de repos et de guet pour les félins. Le sol est recouvert d\'herbes hautes, créant un terrain idéal pour la chasse et l\'exploration. Des zones sablonneuses permettent aux lions de s\'allonger confortablement et de se prélasser au soleil.\r\n\r\nDes barrières naturelles, telles que des collines artificielles et des fossés, sont intégrées pour assurer la sécurité tout en offrant un sentiment d\'ouverture. Des structures telles que des grottes et des abris en pierre imitent les refuges naturels, permettant aux lions de se retirer et de se cacher. Les visiteurs peuvent observer les lions depuis des plateformes surélevées ou des chemins sécurisés, offrant une vue plongeante sur l\'enclos sans déranger les animaux. Ce cadre recrée les interactions sociales et les comportements naturels des lions, offrant une expérience immersive tout en sensibilisant le public à la conservation de ces majestueux prédateurs.','L\'enclos des lions offre un espace naturel pour l\'exploration et la chasse.','2024-09-04 08:13:19','2024-09-04 08:13:19',NULL,1);
INSERT INTO `enclosure` VALUES (2,'Enclos des jaguars','Elle couvre les aspects essentiels tels que la taille, l\'aménagement, la végétation, les structures d\'enrichissement, et les mesures de sécurité. Cette conception vise à reproduire au mieux l\'habitat naturel des jaguars tout en assurant leur bien-être et la sécurité des visiteurs et du personnel.','L\'habitat naturel des jaguars comporte un sol en terre avec zones herbeuses et rocheuses. Nombreux arbres et structures en bois pour grimper.','2024-09-04 08:13:19','2024-09-04 08:13:19',NULL,2);
INSERT INTO `enclosure` VALUES (3,'Enclos des caïmans','L\'enclos des caïmans est un espace semi-aquatique recréant leur habitat naturel de marécage. Il comprend une grande étendue d\'eau stagnante avec des zones de végétation dense, des rochers pour se prélasser','L\'enclos des caïmans est un espace semi-aquatique recréant leur habitat naturel de marécage.','2024-09-04 08:13:19','2024-09-04 08:13:19',NULL,3);
INSERT INTO `enclosure` VALUES (4,'Enclos des pandas roux','L\'enclos des pandas roux est conçu pour reproduire fidèlement leur habitat naturel de montagne, offrant une forêt dense de bambous, d\'arbres et de buissons. De nombreux arbres à feuilles persistantes, tels que les pins et les chênes, sont stratégiquement plantés pour fournir des opportunités de grimper, un comportement essentiel pour ces animaux arboricoles. Les branches des arbres sont renforcées pour supporter le poids des pandas roux et sont disposées de manière à créer des parcours de grimpe stimulants et variés.\r\n\r\nDes abris naturels, tels que des cavités d\'arbres et des niches en bois, sont répartis dans l\'enclos pour permettre aux pandas roux de se reposer et de se cacher, mimant les crevasses et les nids qu\'ils utiliseraient dans la nature. Le sol est couvert de feuillage et de sous-bois épais pour offrir un environnement confortable et enrichissant.','L\'enclos de panda roux est constitué d\'une forêt de bambous et d\'arbres, offrant des branches pour grimper et des abris naturels pour se reposer','2024-09-04 08:13:19','2024-09-04 08:13:19',NULL,2);
INSERT INTO `enclosure` VALUES (5,'Enclos des éléphants','Cet espace fournit de l\'ombre et des sources de nourriture, il inclut plusieurs points d\'eau, comme des étangs et des mares, où les éléphants peuvent se baigner, boire et jouer, recréant leur comportement naturel de socialisation et de toilettage. Des terrains variés, allant de sols sablonneux à des zones herbeuses, permettent aux éléphants de marcher et de creuser.\r\n\r\nDes troncs d\'arbres abattus et des structures en bois sont disséminés pour permettre aux éléphants de se frotter, de se gratter et de démontrer leur force en les manipulant. Les barrières naturelles et les collines artificielles offrent une séparation subtile entre les éléphants et les visiteurs, qui peuvent les observer depuis des plateformes sécurisées et surélevées. L\'enclos est conçu pour promouvoir le bien-être des éléphants, en leur offrant un environnement enrichissant et stimulant qui encourage les comportements naturels et favorise la santé physique et mentale de ces majestueux animaux.','L\'enclos des éléphants offre des points d\'eau, permettant aux éléphants de se nourrir, se baigner et de se socialiser.','2024-09-04 08:13:19','2024-09-04 08:13:19',NULL,1);
INSERT INTO `enclosure` VALUES (6,'Enclos des zèbres','Les zèbres peuvent se déplacer librement sur un terrain légèrement vallonné, offrant une diversité de paysages et des zones d\'ombre pour se protéger du soleil.\r\n\r\nDes mares et des points d\'eau sont intégrés pour permettre aux zèbres de boire et de se rafraîchir. Des barrières naturelles, comme des fossés et des haies, sont utilisées pour délimiter l\'enclos tout en maintenant une esthétique naturelle. Des zones de terre battue permettent aux zèbres de se rouler, un comportement essentiel pour leur toilettage et leur confort. Les visiteurs peuvent observer les zèbres depuis des sentiers ou des plateformes surélevées, leur offrant une vue imprenable sur les animaux sans perturber leur environnement. Cet enclos vise à recréer les conditions naturelles de la savane, permettant aux zèbres d\'exprimer leurs comportements sociaux et naturels tout en sensibilisant le public à leur conservation.','L\'enclos des zèbres offre un espace ouvert pour la course et le pâturage.','2024-09-04 08:13:19','2024-09-04 08:13:19',NULL,1);
/*!40000 ALTER TABLE `enclosure` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `food`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quantity` float DEFAULT NULL,
  `meal_time` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `food_type` varchar(255) DEFAULT NULL,
  `instructions` longtext,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `animal_id` int DEFAULT NULL,
  `prescribed_by_id` int DEFAULT NULL,
  `prescribedBy_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D43829F78E962C16` (`animal_id`),
  KEY `IDX_D43829F7424D1A3F` (`prescribed_by_id`),
  KEY `FK_food_prescribedBy` (`prescribedBy_id`),
  CONSTRAINT `FK_D43829F7424D1A3F` FOREIGN KEY (`prescribed_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_food_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  CONSTRAINT `FK_food_prescribedBy` FOREIGN KEY (`prescribedBy_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `food` WRITE;
/*!40000 ALTER TABLE `food` DISABLE KEYS */;
/*!40000 ALTER TABLE `food` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `food_administration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food_administration` (
  `id` int NOT NULL AUTO_INCREMENT,
  `administration_date` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `quantity_administered` float DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `administered_by_id` int DEFAULT NULL,
  `administeredBy_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F18CC2D32753AB70` (`administered_by_id`),
  KEY `FK_food_administration_administeredBy` (`administeredBy_id`),
  CONSTRAINT `FK_F18CC2D32753AB70` FOREIGN KEY (`administered_by_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_food_administration_administeredBy` FOREIGN KEY (`administeredBy_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `food_administration` WRITE;
/*!40000 ALTER TABLE `food_administration` DISABLE KEYS */;
/*!40000 ALTER TABLE `food_administration` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `food_administration_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food_administration_link` (
  `food_id` int NOT NULL,
  `food_administration_id` int NOT NULL,
  PRIMARY KEY (`food_id`,`food_administration_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `food_administration_link` WRITE;
/*!40000 ALTER TABLE `food_administration_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `food_administration_link` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `habitat` WRITE;
/*!40000 ALTER TABLE `habitat` DISABLE KEYS */;
INSERT INTO `habitat` VALUES (1,'Savane','Cet espace est caractérisé par des prairies ondulantes parsemées de quelques arbres et arbustes, comme les acacias, qui offrent de l\'ombre et des points de repère visuels. Le sol est souvent sec et sablonneux, avec des zones boueuses en saison humide pour simuler les points d\'eau naturels.\r\n\r\nLa diversité des plantes est sélectionnée pour résister à la sécheresse et aux feux contrôlés, favorisant un écosystème équilibré. Les animaux, tels que les zèbres, girafes, éléphants, et antilopes, y cohabitent de manière à refléter les interactions complexes et les comportements sociaux observés dans la nature, tandis que les prédateurs que les lions vivent à part.\r\n\r\nDes structures comme des termitières artificielles et des rochers ajoutent à l\'authenticité de l\'environnement.\r\n\r\nDes mesures de conservation et de gestion durable sont mises en place pour assurer le bien-être des animaux et la pérennité de cet écosystème. Les visiteurs peuvent observer les animaux depuis des plateformes surélevées ou des véhicules de safari, leur permettant de découvrir la richesse et la dynamique de la savane tout en respectant l\'espace des animaux. Ce cadre éducatif et récréatif vise à sensibiliser le public à la beauté et à la fragilité des écosystèmes de savane, tout en contribuant à la conservation des espèces menacées.','L\'habitat savane est une vaste étendue herbeuse avec quelques arbres clairsemés, recréant les conditions naturelles pour abriter des espèces telles que les lions, zèbres et éléphants.','2024-07-24 08:13:19','2024-07-24 08:13:19',NULL);
INSERT INTO `habitat` VALUES (2,'Forêt tropical','L\'habitat de la forêt tropicale est un écosystème luxuriant et diversifié, caractérisé par une végétation dense et une canopée épaisse qui crée un environnement ombragé en dessous. Ce type d\'habitat est dominé par des arbres à feuilles persistantes qui atteignent des hauteurs impressionnantes, formant plusieurs couches de végétation.\r\n\r\nLa canopée supérieure abrite une grande variété d\'espèces d\'oiseaux, d\'insectes et de petits mammifères, comme les singes et les écureuils volants. Les plantes épiphytes, telles que les orchidées et les broméliacées, poussent sur les branches des arbres, ajoutant une dimension verticale à la biodiversité de la forêt.\r\n\r\nAu niveau du sol, le sous-bois est rempli de fougères, de palmiers et de jeunes arbres en croissance. La lumière du soleil atteint rarement le sol de la forêt, créant un environnement humide et frais, propice à une diversité d\'organismes tels que les amphibiens, les reptiles et une variété d\'insectes. Les grands mammifères, comme les jaguars, les tapirs et les éléphants de forêt, y trouvent abri et nourriture.\r\n\r\nLes mesures de conservation et de gestion durable sont essentielles pour protéger la biodiversité de la forêt tropicale et garantir la survie des espèces menacées. Les visiteurs peuvent explorer la forêt à travers des sentiers balisés ou des passerelles suspendues, leur permettant de découvrir la richesse et la beauté de cet écosystème tout en minimisant leur impact sur l\'environnement.','L\'habitat tropical recréant une forêt dense et luxuriante, caractérisée par un climat chaud et humide toute l\'année, de nombreuses plantes exotiques et une faune variée, incluant des oiseaux colorés, des singes, et autres primates','2024-07-24 08:15:34','2024-07-24 08:15:34',NULL);
INSERT INTO `habitat` VALUES (3,'Marécage','L\'habitat  marécage est un écosystème humide et riche en biodiversité, caractérisé par une végétation dense et une abondance d\'eau stagnante ou en mouvement lent. Les marécages se trouvent généralement dans les zones basses et mal drainées, où le sol est saturé en eau pendant une grande partie de l\'année.\r\n\r\nL\'habitat  marécage abrite une variété d\'animaux adaptés à des conditions humides. Les amphibiens, comme les grenouilles et les salamandres, prospèrent dans cet environnement, utilisant l\'eau pour se reproduire et les zones humides pour se nourrir. Les reptiles, tels que les tortues et les alligators, sont également courants, trouvant refuge et nourriture dans les marais.\r\n\r\nLes mammifères, comme les castors, les ratons laveurs et les loutres, exploitent les ressources abondantes des marécages pour se nourrir et construire leurs abris. Les castors, en particulier, jouent un rôle écologique clé en modifiant le paysage avec leurs barrages, créant ainsi des habitats pour d\'autres espèces.\r\n\r\nLes marécages remplissent des fonctions écologiques essentielles, notamment la purification de l\'eau, le contrôle des inondations et la séquestration du carbone. Ils agissent comme des éponges naturelles, absorbant l\'excès d\'eau lors des périodes de fortes pluies et réduisant ainsi les risques d\'inondation en aval.\r\n\r\nLes mesures de conservation et de gestion durable sont cruciales pour préserver les marécages et les services écosystémiques qu\'ils fournissent. Les visiteurs peuvent explorer les marécages via des sentiers surélevés ou en canoë, ce qui leur permet d\'observer de près la diversité des espèces et les interactions écologiques sans perturber l\'habitat fragile. Ces infrastructures permettent également une visite en toute sécurité, offrant une expérience enrichissante tout en minimisant les risques.','L\'habitat marécage est une zone humide avec des plantes aquatiques, abritant des oiseaux, reptiles et amphibiens.','2024-07-24 08:15:53','2024-07-24 08:15:53',NULL);
/*!40000 ALTER TABLE `habitat` ENABLE KEYS */;
UNLOCK TABLES;
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
  CONSTRAINT `FK_image_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  CONSTRAINT `FK_image_enclosure` FOREIGN KEY (`enclosure_id`) REFERENCES `enclosure` (`id`),
  CONSTRAINT `FK_image_habitat` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`),
  CONSTRAINT `FK_image_service` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
INSERT INTO `image` VALUES (3,'foret-tropical-66a0b826315e6325045385.jpg','foret-tropical.jpg',224822,'image/jpeg','2024-07-24 08:15:34','2024-07-24 08:15:34',NULL,2,NULL,NULL);
INSERT INTO `image` VALUES (4,'marecage-66a0b839bd27c921110822.jpg','marecage.jpg',193079,'image/jpeg','2024-07-24 08:15:53','2024-07-24 08:15:53',NULL,3,NULL,NULL);
INSERT INTO `image` VALUES (5,'lion-500x750-66a0bb38ec316593006673.jpg','lion 500x750.jpg',70123,'image/jpeg','2024-07-24 08:28:40','2024-07-24 08:28:40',1,NULL,NULL,NULL);
INSERT INTO `image` VALUES (76,'savane-750x500-66a38e13edb1c314963776.jpg','savane 750x500.jpg',138401,'image/jpeg','2024-07-26 11:52:51','2024-07-26 11:52:51',NULL,1,NULL,NULL);
INSERT INTO `image` VALUES (84,'lion-v3-66a38f67963df403742430.jpg','lion-v3.jpg',61238,'image/jpeg','2024-07-26 11:58:31','2024-07-26 11:58:31',1,NULL,NULL,NULL);
INSERT INTO `image` VALUES (87,'caiman-1-66a399f9da089567703840.jpg','caiman (1).jpg',104032,'image/jpeg','2024-07-26 12:43:37','2024-07-26 12:43:37',2,NULL,NULL,NULL);
INSERT INTO `image` VALUES (88,'panda-roux-66a39af753c27601529226.jpg','panda-roux.jpg',128691,'image/jpeg','2024-07-26 12:47:51','2024-07-26 12:47:51',3,NULL,NULL,NULL);
INSERT INTO `image` VALUES (89,'elephant-66a39b3f4dca8352210543.jpg','elephant.jpg',137951,'image/jpeg','2024-07-26 12:49:03','2024-07-26 12:49:03',4,NULL,NULL,NULL);
INSERT INTO `image` VALUES (90,'zebras-4258909-1280-66a39b7989cfa152462236.jpg','zebras-4258909_1280.jpg',156656,'image/jpeg','2024-07-26 12:50:01','2024-07-26 12:50:01',5,NULL,NULL,NULL);
INSERT INTO `image` VALUES (93,'restaurant-66ac715005a77977326646.jpg','restaurant.jpg',81396,'image/jpeg','2024-08-02 05:40:31','2024-08-02 05:40:31',NULL,NULL,2,NULL);
INSERT INTO `image` VALUES (95,'visite-guide-66ac97f9a47bf016728655.jpg','visite guide.jpg',421190,'image/jpeg','2024-08-02 08:25:29','2024-08-02 08:25:29',NULL,NULL,4,NULL);
INSERT INTO `image` VALUES (97,'couple-lion-66dd7f8657fea721695563.jpg','couple-lion.jpg',94851,'image/jpeg','2024-09-08 12:42:14','2024-09-08 12:42:14',NULL,NULL,NULL,1);
INSERT INTO `image` VALUES (99,'enclos-caiman-66dfdf8c0ed01849178489.jpeg','enclos-caïman.jpeg',105874,'image/jpeg','2024-09-10 07:56:28','2024-09-10 07:56:28',NULL,NULL,NULL,3);
INSERT INTO `image` VALUES (101,'lion-v3-66e1994978726952236311.jpg','lion-v3.jpg',106136,'image/jpeg','2024-09-11 15:21:13','2024-09-11 15:21:13',1,NULL,NULL,NULL);
INSERT INTO `image` VALUES (102,'avatar-man-66e7cb95d8340366269111.jpg','avatar-man.jpg',45428,'image/jpeg','2024-09-16 08:09:25','2024-09-16 08:09:25',NULL,NULL,NULL,NULL);
INSERT INTO `image` VALUES (103,'avatar-woman-66e7cbb6caaaa597356610.jpg','avatar-woman.jpg',62359,'image/jpeg','2024-09-16 08:09:58','2024-09-16 08:09:58',NULL,NULL,NULL,NULL);
INSERT INTO `image` VALUES (104,'avatar-man-66e7cbcd899b7120225243.jpg','avatar-man.jpg',45428,'image/jpeg','2024-09-16 08:10:21','2024-09-16 08:10:21',NULL,NULL,NULL,NULL);
INSERT INTO `image` VALUES (105,'couple-lions-v2-66e7cc2655ef3911924714.jpg','couple-lions-v2.jpg',141301,'image/jpeg','2024-09-16 08:11:50','2024-09-16 08:11:50',NULL,NULL,NULL,1);
INSERT INTO `image` VALUES (106,'enclos-jaguars-66f3f85943855015456732.jpg','enclos-jaguars.jpg',178518,'image/jpeg','2024-09-25 13:47:37','2024-09-25 13:47:37',NULL,NULL,NULL,2);
INSERT INTO `image` VALUES (107,'enclos-elephants-66f3fa714480c636033210.jpg','enclos-elephants.jpg',164933,'image/jpeg','2024-09-25 13:56:33','2024-09-25 13:56:33',NULL,NULL,NULL,5);
INSERT INTO `image` VALUES (108,'enclos-zebres-66f3fabe0fd73376091732.jpg','enclos-zebres.jpg',178187,'image/jpeg','2024-09-25 13:57:50','2024-09-25 13:57:50',NULL,NULL,NULL,6);
INSERT INTO `image` VALUES (109,'enclos-panda-roux-66f3faff55fe4358780335.jpg','enclos-panda-roux.jpg',89442,'image/jpeg','2024-09-25 13:58:55','2024-09-25 13:58:55',NULL,NULL,NULL,4);
INSERT INTO `image` VALUES (110,'train-6720f356a50d2316830512.jpg','train.jpg',186282,'image/jpeg','2024-10-29 14:38:14','2024-10-29 14:38:14',NULL,NULL,6,NULL);
INSERT INTO `image` VALUES (111,'panda-roux-2-6720f394571f9665524778.jpg','panda-roux-2.jpg',46609,'image/jpeg','2024-10-29 14:39:16','2024-10-29 14:39:16',7,NULL,NULL,NULL);
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `notice` WRITE;
/*!40000 ALTER TABLE `notice` DISABLE KEYS */;
/*!40000 ALTER TABLE `notice` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext,
  `short_description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (2,'Restauration','Bienvenue au restaurant :  \"La Savane Gourmande\", votre oasis culinaire au cœur du zoo! Offrant une expérience gastronomique unique, notre restaurant vous invite à déguster une variété de plats inspirés des cuisines du monde, tout en profitant d\'une vue imprenable sur les habitats des animaux exotiques. Que vous souhaitiez savourer un repas copieux après une journée d\'aventure ou simplement prendre une pause avec une collation rafraîchissante, \"La Savane Gourmande\" est l\'endroit idéal pour petits et grands. Rejoignez-nous pour une escapade savoureuse en pleine nature!','le restaurant la Savane Gourmande !','2024-07-24 09:24:50','2024-07-24 09:24:50',NULL);
INSERT INTO `service` VALUES (4,'Visite guidée','Rejoignez-nous pour une visite guidée captivante du zoo ! Découvrez des animaux fascinants, apprenez des faits étonnants et explorez les habitats recréés avec soin. Accompagné par un guide expert, plongez dans les secrets de la faune mondiale et vivez une expérience enrichissante et éducative. Parfait pour les familles, les amateurs de nature et les curieux de tout âge.','Visitez gratuitement nos habitats animaliers avec un guide expert !','2024-08-02 08:25:29','2024-08-02 08:25:29',NULL);
INSERT INTO `service` VALUES (6,'Visite du zoo en petit train','Lors de la visite du zoo en petit train, vous découvrirez les différents espaces animaliers d\'une manière ludique et confortable. Le parcours vous emmène à travers les zones les plus emblématiques du parc, où vous pourrez observer de nombreux animaux de près, tels que les lions, les éléphants, les girafes et bien d\'autres encore. Des commentaires diffusés tout au long du trajet vous fourniront des informations intéressantes sur les espèces, leur habitat et leurs comportements. Cette visite est idéale pour les familles, les groupes scolaires ou toute personne souhaitant profiter d\'une expérience immersive sans avoir à marcher longuement dans le parc.','Parcourez le parc confortablement en petit train ! Circuit de 30 minutes avec arrêts aux points clés du zoo.','2024-10-29 14:38:14','2024-10-29 14:38:14',NULL);
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'fxd@gmail.com','darragon','darragon','[\"ROLE_ADMIN\"]','$2y$13$T03dnXDYdj.9.QvLyIGJC.Pw4N5nmBfRrqbbAt6C81UUie9ZCYYsO',667879458,'{\"zip\": \"15130\", \"city\": \"YTRAC\", \"street\": \"Rue du Puy de Peyre Arse\", \"complement\": null}',NULL,'2024-07-25 05:12:25','2024-07-25 05:12:25',NULL,102);
INSERT INTO `user` VALUES (3,'fxdworker@gmail.com','fx','dd','[\"ROLE_WORKER\"]','$2y$13$TeC79G/YmYR678YJinaQ3u9Frz8oFHNUI5exHkykWyNzq9jfcfLse',NULL,'{\"zip\": null, \"city\": null, \"street\": null, \"complement\": null}',NULL,'2024-07-31 15:11:48','2024-07-31 15:11:48',NULL,103);
INSERT INTO `user` VALUES (4,'fxdveto@gmail.com','fx','dd','[\"ROLE_VETERINARY\"]','$2y$13$smpYSPUqa2ehucZEXMewxuU5BbKpWGNvq6EeMB/IozGhNBYf19A2i',NULL,'{\"zip\": null, \"city\": null, \"street\": null, \"complement\": null}',NULL,'2024-08-01 11:19:31','2024-08-01 11:19:31',NULL,104);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `veterinary_report` WRITE;
/*!40000 ALTER TABLE `veterinary_report` DISABLE KEYS */;
INSERT INTO `veterinary_report` VALUES (2,'Simba, le lion, est en parfaite santé selon l\'examen vétérinaire réalisé. Il présente une condition corporelle excellente avec une musculature bien développée et un pelage brillant sans signes d\'irritation ou de parasites. Son comportement est actif et alerte, et son appétit ainsi que sa consommation d\'eau sont normaux. Les examens cliniques montrent une respiration claire, des battements cardiaques réguliers, et aucune anomalie dans les systèmes musculo-squelettique, gastro-intestinal, urinaire ou reproducteur. Les analyses sanguines et les tests des selles et des urines sont tous dans les limites normales, confirmant l\'absence de maladies ou de troubles. Il est recommandé de maintenir une alimentation équilibrée, de promouvoir un exercice régulier et de prévoir des visites de routine tous les six mois pour le suivi général et les vaccinations préventives. Simba est en excellente forme physique et mentale.','2024-08-01 11:41:05','2024-08-01 11:41:05',NULL,NULL,1);
INSERT INTO `veterinary_report` VALUES (3,'TEST','2024-09-10 07:42:59','2024-09-10 07:42:59',NULL,NULL,2);
INSERT INTO `veterinary_report` VALUES (4,'tous va bien','2024-10-01 06:51:34','2024-10-01 06:51:34',NULL,NULL,7);
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

