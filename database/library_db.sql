-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: library_db
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `book` (
  `book_id` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` varchar(17) NOT NULL,
  `title` varchar(45) NOT NULL,
  `author` varchar(45) NOT NULL,
  `pub_year` varchar(45) NOT NULL,
  `description` text,
  `cover_page` text,
  `qty` int NOT NULL,
  `available_qty` int NOT NULL,
  `category_id` int NOT NULL,
  `status_id` int NOT NULL,
  `language_id` int NOT NULL,
  PRIMARY KEY (`book_id`),
  UNIQUE KEY `book_id_UNIQUE` (`book_id`),
  KEY `fk_book_category1_idx` (`category_id`),
  KEY `fk_book_status1_idx` (`status_id`),
  KEY `fk_book_language1_idx` (`language_id`),
  CONSTRAINT `fk_book_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  CONSTRAINT `fk_book_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`),
  CONSTRAINT `fk_book_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book`
--

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;
INSERT INTO `book` VALUES ('B-000001','978-0061120084','To Kill a Mockingbird','Harper Lee','1960','A story about racial injustice and the loss of innocence in a small town in the southern United States.','67c8038bdde32_1.jpg',1,0,1,1,2),('B-000002','978-8193182085','Siluvai','Sudha Murugan','2007','A poignant Tamil novel about family bonds and the conflicts within, delving into human emotions and life struggles.','67c804747bf16_3.jpeg',15,15,3,1,3),('B-000003','9552023785','තුංමං හන්දිය','Mahagama Sekara','2008','The story revolves around a simple yet profound narrative of a young boy and his emotional journey as he struggles with the challenges of life, relationships, and societal expectations. The book explores themes of love, innocence, and the quest for identity, drawing readers into the poignant experiences of the protagonist.','67c8061619930_4.jpeg',20,20,4,1,1),('B-000004','978-0451205767','The Godfather','Mario Puzo','1969','A powerful crime drama set around the Corleone family, touching upon themes of loyalty, crime, and the Mafia.','67c806db3ead7_5.jpeg',10,9,6,1,2),('B-000005','978-0618640157','The Lord of the Rings','Tolkien','2000','An epic high-fantasy novel following Frodo’s quest to destroy the One Ring.','67c847cb1a418_7.jpg',6,5,7,1,2),('B-000006','978-0439708180','Harry Potter and the Sorcerer’s Stone','JKRowling','2000','The first book in the Harry Potter series, following a young wizard’s journey at Hogwarts.','67c8485c54f35_8.jpeg',3,3,7,1,2),('B-000007','978-0-618-25727-9','Unfinished Tales','Tolkien','2012','Includes incomplete and alternative versions of stories, providing deeper insights into Tolkien’s world-building. Edited by his son, Christopher Tolkien, the book explores untold histories of Middle-earth, including the backstory of Gandalf and the origins of the Istari (wizards), as well as events from The Silmarillion and The Lord of the Rings.','67c849c33633a_9.jpeg',3,2,7,1,2),('B-000008','978-624-99968-0-7','DUO','Surath de Mel','2023','\"Duo\" explores the profound impact of Sri Lanka\'s turbulent history on individual lives. The novel delves into the protracted civil war in the north and the 1988-89 insurgency in the south, highlighting how these events eroded the social fabric of Sinhala Buddhist society. Through the experiences of characters like Uma, Depika, and Sarah, de Mel portrays the indelible consequences of these conflicts, offering readers a contemplative and immersive experience. ','67c84ba158b92_6.jpg',10,6,1,1,1),('B-000009','0-307-26543-9','The Road','Cormac McCarthy','2012','The Road is a 2006 post-apocalyptic novel by American writer Cormac McCarthy. The book details the grueling journey of a father and his young son over several months across a landscape blasted by an unspecified cataclysm that has destroyed industrial civilization and nearly all life.','67c8584f79780_10.jpg',10,6,1,1,2),('B-000010','9780718157838','Me Before You','Jojo Moyes','2012','ouisa Clark takes a job caring for Will Traynor, a once-active man left paralyzed after an accident. Their relationship blossoms in unexpected ways, teaching them both about love, courage, and the value of life even when the odds seem impossible.','67f225a3d8c8e_11.jpg',2,2,13,1,2);
/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `borrow`
--

DROP TABLE IF EXISTS `borrow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `borrow` (
  `borrow_id` int NOT NULL AUTO_INCREMENT,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `borrow_book_id` varchar(12) NOT NULL,
  `borrow_member_id` int NOT NULL,
  PRIMARY KEY (`borrow_id`),
  KEY `fk_borrow_book1_idx` (`borrow_book_id`),
  KEY `fk_borrow_member1_idx` (`borrow_member_id`),
  CONSTRAINT `fk_borrow_book1` FOREIGN KEY (`borrow_book_id`) REFERENCES `book` (`book_id`),
  CONSTRAINT `fk_borrow_member1` FOREIGN KEY (`borrow_member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrow`
--

LOCK TABLES `borrow` WRITE;
/*!40000 ALTER TABLE `borrow` DISABLE KEYS */;
INSERT INTO `borrow` VALUES (35,'2025-04-04','2025-04-04','2025-04-06','B-000007',1),(36,'2025-04-07','2025-04-21',NULL,'B-000001',1),(37,'2025-04-06','2025-04-20',NULL,'B-000009',1);
/*!40000 ALTER TABLE `borrow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(45) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Fiction'),(2,'Philosophy'),(3,'Literature'),(4,'Poetry'),(6,'Thriller'),(7,'Fantacy'),(12,'Comic'),(13,'Romance');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fines`
--

DROP TABLE IF EXISTS `fines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fines` (
  `fine_id` int NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `fine_borrow_id` int NOT NULL,
  `fine_member_id` int NOT NULL,
  PRIMARY KEY (`fine_id`),
  KEY `fk_fines_borrow1_idx` (`fine_borrow_id`),
  KEY `fk_fines_member1_idx` (`fine_member_id`),
  CONSTRAINT `fk_fines_borrow1` FOREIGN KEY (`fine_borrow_id`) REFERENCES `borrow` (`borrow_id`),
  CONSTRAINT `fk_fines_member1` FOREIGN KEY (`fine_member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fines`
--

LOCK TABLES `fines` WRITE;
/*!40000 ALTER TABLE `fines` DISABLE KEYS */;
INSERT INTO `fines` VALUES (9,10,35,1);
/*!40000 ALTER TABLE `fines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `language` (
  `language_id` int NOT NULL AUTO_INCREMENT,
  `language_name` varchar(45) NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language`
--

LOCK TABLES `language` WRITE;
/*!40000 ALTER TABLE `language` DISABLE KEYS */;
INSERT INTO `language` VALUES (1,'Sihnala'),(2,'English'),(3,'Tamil'),(4,'Other');
/*!40000 ALTER TABLE `language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `library_info`
--

DROP TABLE IF EXISTS `library_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `library_info` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `logo` text NOT NULL,
  `address` text NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(1004) NOT NULL,
  `membership_fee` double NOT NULL,
  `fine_amount` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library_info`
--

LOCK TABLES `library_info` WRITE;
/*!40000 ALTER TABLE `library_info` DISABLE KEYS */;
INSERT INTO `library_info` VALUES (1,'SHELF LOOM','logo.png','University of Sri Jayewardenepura, Gangodawila, Nugegoda','0111234567','dulakshigamma@gmail.com',1000,5);
/*!40000 ALTER TABLE `library_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(12) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(60) NOT NULL,
  `date_joined` date NOT NULL,
  `vcode` text,
  `profile_img` text,
  `status_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_member_status1_idx` (`status_id`),
  CONSTRAINT `fk_member_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'200180300611','Kamal','Rathnayake','0704567123','No, 134, cross road, Kurunagala','dulakshigamma@gmail.com','2025-03-07',NULL,'67c91b567efe0_download.jpeg',1),(19,'200178377654','mm','ll','0711232124','mmddd','dulakshmma@gmail.com','2025-04-04',NULL,NULL,1);
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_login`
--

DROP TABLE IF EXISTS `member_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_login` (
  `member_login_id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `memberId` int NOT NULL,
  PRIMARY KEY (`member_login_id`),
  UNIQUE KEY `member_id_UNIQUE` (`member_id`),
  KEY `fk_member_login_member1_idx` (`memberId`),
  CONSTRAINT `fk_member_login_member1` FOREIGN KEY (`memberId`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_login`
--

LOCK TABLES `member_login` WRITE;
/*!40000 ALTER TABLE `member_login` DISABLE KEYS */;
INSERT INTO `member_login` VALUES (1,'M-000001','Dcg$11029',1),(26,'M-000002',']*W2o}J<i<&F',19);
/*!40000 ALTER TABLE `member_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_saved_book`
--

DROP TABLE IF EXISTS `member_saved_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_saved_book` (
  `save_id` int NOT NULL AUTO_INCREMENT,
  `saved_member_id` int NOT NULL,
  `saved_book_id` varchar(12) NOT NULL,
  PRIMARY KEY (`save_id`,`saved_member_id`,`saved_book_id`),
  KEY `fk_member_has_book_book1_idx` (`saved_book_id`),
  KEY `fk_member_has_book_member1_idx` (`saved_member_id`),
  CONSTRAINT `fk_member_has_book_book1` FOREIGN KEY (`saved_book_id`) REFERENCES `book` (`book_id`),
  CONSTRAINT `fk_member_has_book_member1` FOREIGN KEY (`saved_member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_saved_book`
--

LOCK TABLES `member_saved_book` WRITE;
/*!40000 ALTER TABLE `member_saved_book` DISABLE KEYS */;
INSERT INTO `member_saved_book` VALUES (18,1,'B-000005');
/*!40000 ALTER TABLE `member_saved_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module` (
  `module_id` int NOT NULL AUTO_INCREMENT,
  `module_name` varchar(45) NOT NULL,
  `module_icon` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'Staff Management','man.png'),(2,'Book Management','reading-book.png'),(3,'Circulation Management','book.png'),(4,'Member Management','team.png'),(5,'Payment Management','credit-card.png');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `image_path` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'New Book Arrived','2025-03-28','The road by cormac maccarthy was arrived. Explore our collection for more actions.','box1.jpg');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `status` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL,
  `receiver_id` varchar(45) NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification`
--

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
INSERT INTO `notification` VALUES (1,'dd','read','2025-04-01 17:32:22','M-000001'),(2,'Welcome to Shelf Loom!','read','2025-04-01 17:56:09','M-000001'),(3,'Your membership will expire soon.','read','2025-04-01 17:56:09','M-000001'),(4,'A new book has been added!','read','2025-04-01 17:56:09','M-000001'),(5,'$subject','read','2025-04-03 05:14:14','M-000001'),(6,'$subject','read','2025-04-03 07:02:44','M-000001'),(7,'fffffff','read','2025-04-03 07:33:49','M-000001'),(8,'Send Mail','unread','2025-04-03 17:05:28','M-000001'),(9,'d','unread','2025-04-06 16:19:09','M-000001'),(10,'Send Mail','unread','2025-04-06 16:21:59','M-000001');
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opening_hours`
--

DROP TABLE IF EXISTS `opening_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `opening_hours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `day` varchar(45) NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opening_hours`
--

LOCK TABLES `opening_hours` WRITE;
/*!40000 ALTER TABLE `opening_hours` DISABLE KEYS */;
INSERT INTO `opening_hours` VALUES (1,'Week Day','08:00:00','17:00:00'),(2,'Week End','08:00:00','14:00:00'),(3,'Holiday','00:00:00','00:00:00');
/*!40000 ALTER TABLE `opening_hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `payed_at` datetime NOT NULL,
  `next_due_date` datetime NOT NULL,
  `memberId` int NOT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `trasaction_id_UNIQUE` (`transaction_id`),
  KEY `fk_payment_member1_idx` (`memberId`),
  CONSTRAINT `fk_payment_member1` FOREIGN KEY (`memberId`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (8,1000,'1111','2025-04-01 19:58:17','2026-03-01 19:58:18',1),(9,1000,'67f0063fecb2d','2025-04-04 21:48:58','2026-04-04 21:48:58',19);
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservation` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `reservation_date` date NOT NULL,
  `reservation_member_id` int NOT NULL,
  `reservation_book_id` varchar(12) NOT NULL,
  `status_id` int NOT NULL,
  `notified_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `fk_resevation_book1_idx` (`reservation_book_id`),
  KEY `fk_reservation_reservation_status1_idx` (`status_id`),
  KEY `fk_reservation_member1_idx` (`reservation_member_id`),
  CONSTRAINT `fk_reservation_member1` FOREIGN KEY (`reservation_member_id`) REFERENCES `member` (`id`),
  CONSTRAINT `fk_reservation_reservation_status1` FOREIGN KEY (`status_id`) REFERENCES `reservation_status` (`status_id`),
  CONSTRAINT `fk_resevation_book1` FOREIGN KEY (`reservation_book_id`) REFERENCES `book` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
INSERT INTO `reservation` VALUES (47,'2025-04-04',1,'B-000009',2,NULL,'2025-04-11'),(48,'2025-04-04',1,'B-000007',4,NULL,'2025-04-11');
/*!40000 ALTER TABLE `reservation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation_status`
--

DROP TABLE IF EXISTS `reservation_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservation_status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation_status`
--

LOCK TABLES `reservation_status` WRITE;
/*!40000 ALTER TABLE `reservation_status` DISABLE KEYS */;
INSERT INTO `reservation_status` VALUES (1,'Reserved'),(2,'Collected'),(3,'Expired'),(4,'Canceled'),(5,'Waitlist');
/*!40000 ALTER TABLE `reservation_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Librarian'),(2,'Book Keeper');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_module`
--

DROP TABLE IF EXISTS `role_has_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_module` (
  `role_id` int NOT NULL,
  `module_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`module_id`),
  KEY `fk_role_has_module_module1_idx` (`module_id`),
  KEY `fk_role_has_module_role1_idx` (`role_id`),
  CONSTRAINT `fk_role_has_module_module1` FOREIGN KEY (`module_id`) REFERENCES `module` (`module_id`),
  CONSTRAINT `fk_role_has_module_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_module`
--

LOCK TABLES `role_has_module` WRITE;
/*!40000 ALTER TABLE `role_has_module` DISABLE KEYS */;
INSERT INTO `role_has_module` VALUES (1,1),(1,2),(2,2),(1,3),(2,3),(1,4),(2,4),(1,5),(2,5);
/*!40000 ALTER TABLE `role_has_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(12) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(45) NOT NULL,
  `vcode` text,
  `profile_img` text,
  `role_id` int NOT NULL,
  `status_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_role1_idx` (`role_id`),
  KEY `fk_user_status1_idx` (`status_id`),
  CONSTRAINT `fk_user_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`),
  CONSTRAINT `fk_user_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'200180400345','Doe','John','0701234567','ss','ss@gmail.com',NULL,'67f2190a8c800_download.jpeg',1,1),(2,'200180300611','Test1','Test2','0704567123','Main road, Kandy','dulakshigamma@gmail.com','67f2106d17500',NULL,2,1);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_key`
--

DROP TABLE IF EXISTS `staff_key`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_key` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `key_value` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_value_UNIQUE` (`key_value`),
  KEY `fk_staff_key_role1_idx` (`role_id`),
  CONSTRAINT `fk_staff_key_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_key`
--

LOCK TABLES `staff_key` WRITE;
/*!40000 ALTER TABLE `staff_key` DISABLE KEYS */;
INSERT INTO `staff_key` VALUES (3,'dulakshigamma@gmail.com','B06F220789EDCD8FC6B95A7DCB7C3EC5',2),(4,'','E1D9D36E0EDD55CC70149B51569BFA77',2),(5,'','54EBB20E4B8DB8B4BF97DE18AF6C5FFE',2);
/*!40000 ALTER TABLE `staff_key` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_login`
--

DROP TABLE IF EXISTS `staff_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_login` (
  `login_id` int NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `staffId` int NOT NULL,
  PRIMARY KEY (`login_id`),
  KEY `fk_login_user1_idx` (`staffId`),
  CONSTRAINT `fk_login_user1` FOREIGN KEY (`staffId`) REFERENCES `staff` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_login`
--

LOCK TABLES `staff_login` WRITE;
/*!40000 ALTER TABLE `staff_login` DISABLE KEYS */;
INSERT INTO `staff_login` VALUES (1,'S-000001','Dcg$11029',1),(2,'S-000002','Asd!1234',2);
/*!40000 ALTER TABLE `staff_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'Active'),(2,'Deactive'),(3,'Pending'),(4,'Rejected'),(5,'Expired');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'library_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-07 10:37:31
