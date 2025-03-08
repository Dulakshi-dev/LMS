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
INSERT INTO `book` VALUES ('B-000001','978-0061120084','To Kill a Mockingbird','Harper Lee','1960','A story about racial injustice and the loss of innocence in a small town in the southern United States.','67c8038bdde32_1.jpg',10,11,1,1,2),('B-000002','978-8193182085','Siluvai','Sudha Murugan','2007','A poignant Tamil novel about family bonds and the conflicts within, delving into human emotions and life struggles.','67c804747bf16_3.jpeg',15,15,3,1,3),('B-000003','9552023785','තුංමං හන්දිය','Mahagama Sekara','2008','The story revolves around a simple yet profound narrative of a young boy and his emotional journey as he struggles with the challenges of life, relationships, and societal expectations. The book explores themes of love, innocence, and the quest for identity, drawing readers into the poignant experiences of the protagonist. ','67c8061619930_4.jpeg',20,20,4,1,1),('B-000004','978-0451205767','The Godfather','Mario Puzo','1969','A powerful crime drama set around the Corleone family, touching upon themes of loyalty, crime, and the Mafia.','67c806db3ead7_5.jpeg',10,10,6,1,2),('B-000005','978-0618640157','The Lord of the Rings','Tolkien','2000','An epic high-fantasy novel following Frodo’s quest to destroy the One Ring.','67c847cb1a418_7.jpg',5,5,7,1,2),('B-000006','978-0439708180','Harry Potter and the Sorcerer’s Stone','JKRowling','2000','The first book in the Harry Potter series, following a young wizard’s journey at Hogwarts.','67c8485c54f35_8.jpeg',3,3,7,1,2),('B-000007','978-0-618-25727-9','Unfinished Tales','Tolkien','2012','Includes incomplete and alternative versions of stories, providing deeper insights into Tolkien’s world-building. Edited by his son, Christopher Tolkien, the book explores untold histories of Middle-earth, including the backstory of Gandalf and the origins of the Istari (wizards), as well as events from The Silmarillion and The Lord of the Rings.','67c849c33633a_9.jpeg',4,4,7,1,2),('B-000008','978-624-99968-0-7','DUO','Surath de Mel','2023','\"Duo\" explores the profound impact of Sri Lanka\'s turbulent history on individual lives. The novel delves into the protracted civil war in the north and the 1988-89 insurgency in the south, highlighting how these events eroded the social fabric of Sinhala Buddhist society. Through the experiences of characters like Uma, Depika, and Sarah, de Mel portrays the indelible consequences of these conflicts, offering readers a contemplative and immersive experience. ','67c84ba158b92_6.jpg',10,10,1,1,1),('B-000009','0-307-26543-9','The Road','Cormac McCarthy','2012','The Road is a 2006 post-apocalyptic novel by American writer Cormac McCarthy. The book details the grueling journey of a father and his young son over several months across a landscape blasted by an unspecified cataclysm that has destroyed industrial civilization and nearly all life.','67c8584f79780_10.jpg',10,10,1,1,2);
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrow`
--

LOCK TABLES `borrow` WRITE;
/*!40000 ALTER TABLE `borrow` DISABLE KEYS */;
INSERT INTO `borrow` VALUES (17,'2025-03-07','2025-03-14','2025-03-21','B-000001',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Fiction'),(2,'Philosophy'),(3,'Literature'),(4,'Poetry'),(5,'Romance'),(6,'Thriller'),(7,'Fantacy');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fines`
--

LOCK TABLES `fines` WRITE;
/*!40000 ALTER TABLE `fines` DISABLE KEYS */;
INSERT INTO `fines` VALUES (4,35,17,1);
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
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login` (
  `login_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userId` int NOT NULL,
  PRIMARY KEY (`login_id`),
  KEY `fk_login_user1_idx` (`userId`),
  CONSTRAINT `fk_login_user1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login`
--

LOCK TABLES `login` WRITE;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` VALUES (6,'S-000001','Dg$11029',14);
/*!40000 ALTER TABLE `login` ENABLE KEYS */;
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
  `mobile` varchar(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(60) NOT NULL,
  `date_joined` date NOT NULL,
  `vcode` text,
  `profile_img` text,
  `status_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_member_status1_idx` (`status_id`),
  CONSTRAINT `fk_member_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'200180300611','Dulakshi','Gammanpila','0704567123','dkk','dulakshigamma@gmail.com','2025-03-07',NULL,'67c91b567efe0_download.jpeg',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_login`
--

LOCK TABLES `member_login` WRITE;
/*!40000 ALTER TABLE `member_login` DISABLE KEYS */;
INSERT INTO `member_login` VALUES (1,'M-000001','Dg$110299',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_saved_book`
--

LOCK TABLES `member_saved_book` WRITE;
/*!40000 ALTER TABLE `member_saved_book` DISABLE KEYS */;
INSERT INTO `member_saved_book` VALUES (4,1,'B-000001');
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
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'Staff Management'),(2,'Book Management'),(3,'Book Circulation'),(4,'Member Management'),(5,'Reservation Management');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
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
  `member_id` int NOT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `trasaction_id_UNIQUE` (`transaction_id`),
  KEY `fk_payment_member1_idx` (`member_id`),
  CONSTRAINT `fk_payment_member1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (1,1000,'1223','2025-03-06 00:00:00','2025-03-14 00:00:00',1);
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
  `expiration_date` date NOT NULL,
  `reservation_member_id` int NOT NULL,
  `reservation_book_id` varchar(12) NOT NULL,
  `status_id` int NOT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `fk_resevation_book1_idx` (`reservation_book_id`),
  KEY `fk_reservation_reservation_status1_idx` (`status_id`),
  KEY `fk_reservation_member1_idx` (`reservation_member_id`),
  CONSTRAINT `fk_reservation_member1` FOREIGN KEY (`reservation_member_id`) REFERENCES `member` (`id`),
  CONSTRAINT `fk_reservation_reservation_status1` FOREIGN KEY (`status_id`) REFERENCES `reservation_status` (`status_id`),
  CONSTRAINT `fk_resevation_book1` FOREIGN KEY (`reservation_book_id`) REFERENCES `book` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
INSERT INTO `reservation` VALUES (14,'2025-03-05','2025-03-15',1,'B-000001',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation_status`
--

LOCK TABLES `reservation_status` WRITE;
/*!40000 ALTER TABLE `reservation_status` DISABLE KEYS */;
INSERT INTO `reservation_status` VALUES (1,'Reserved'),(2,'Borrowed'),(3,'Expired');
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
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'Active'),(2,'Deactive'),(3,'Pending');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nic` varchar(12) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `mobile` varchar(10) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (14,'200180300611','Dulakshi','Gammanpila','0701234588','No 12, main road, Kandy','dulakshigamma@gmail.com',NULL,'67c880b7bc614_download.jpeg',1,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
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

-- Dump completed on 2025-03-08 23:17:45
