-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.39 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for library_db
DROP DATABASE IF EXISTS `library_db`;
CREATE DATABASE IF NOT EXISTS `library_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `library_db`;

-- Dumping structure for table library_db.book
DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
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

-- Dumping data for table library_db.book: ~9 rows (approximately)
INSERT INTO `book` (`book_id`, `isbn`, `title`, `author`, `pub_year`, `description`, `cover_page`, `qty`, `available_qty`, `category_id`, `status_id`, `language_id`) VALUES
	('B-000001', '978-0061120084', 'To Kill a Mockingbird', 'Harper Lee', '1960', 'A story about racial injustice and the loss of innocence in a small town in the southern United States.', '67c8038bdde32_1.jpg', 1, 0, 1, 1, 2),
	('B-000002', '978-8193182085', 'Siluvai', 'Sudha Murugan', '2007', 'A poignant Tamil novel about family bonds and the conflicts within, delving into human emotions and life struggles.', '67c804747bf16_3.jpeg', 15, 15, 3, 1, 3),
	('B-000003', '9552023785', 'තුංමං හන්දිය', 'Mahagama Sekara', '2008', 'The story revolves around a simple yet profound narrative of a young boy and his emotional journey as he struggles with the challenges of life, relationships, and societal expectations. The book explores themes of love, innocence, and the quest for identity, drawing readers into the poignant experiences of the protagonist.', '67c8061619930_4.jpeg', 20, 20, 4, 1, 1),
	('B-000004', '978-0451205767', 'The Godfather', 'Mario Puzo', '1969', 'A powerful crime drama set around the Corleone family, touching upon themes of loyalty, crime, and the Mafia.', '67c806db3ead7_5.jpeg', 10, 9, 6, 1, 2),
	('B-000005', '978-0618640157', 'The Lord of the Rings', 'Tolkien', '2000', 'An epic high-fantasy novel following Frodo’s quest to destroy the One Ring.', '67c847cb1a418_7.jpg', 6, 5, 7, 1, 2),
	('B-000006', '978-0439708180', 'Harry Potter and the Sorcerer’s Stone', 'JKRowling', '2000', 'The first book in the Harry Potter series, following a young wizard’s journey at Hogwarts.', '67c8485c54f35_8.jpeg', 3, 3, 7, 1, 2),
	('B-000007', '978-0-618-25727-9', 'Unfinished Tales', 'Tolkien', '2012', 'Includes incomplete and alternative versions of stories, providing deeper insights into Tolkien’s world-building. Edited by his son, Christopher Tolkien, the book explores untold histories of Middle-earth, including the backstory of Gandalf and the origins of the Istari (wizards), as well as events from The Silmarillion and The Lord of the Rings.', '67c849c33633a_9.jpeg', 3, 2, 7, 1, 2),
	('B-000008', '978-624-99968-0-7', 'DUO', 'Surath de Mel', '2023', '"Duo" explores the profound impact of Sri Lanka\'s turbulent history on individual lives. The novel delves into the protracted civil war in the north and the 1988-89 insurgency in the south, highlighting how these events eroded the social fabric of Sinhala Buddhist society. Through the experiences of characters like Uma, Depika, and Sarah, de Mel portrays the indelible consequences of these conflicts, offering readers a contemplative and immersive experience. ', '67c84ba158b92_6.jpg', 10, 6, 1, 1, 1),
	('B-000009', '0-307-26543-9', 'The Road', 'Cormac McCarthy', '2012', 'The Road is a 2006 post-apocalyptic novel by American writer Cormac McCarthy. The book details the grueling journey of a father and his young son over several months across a landscape blasted by an unspecified cataclysm that has destroyed industrial civilization and nearly all life.', '67c8584f79780_10.jpg', 10, 6, 1, 1, 2),
	('B-000010', '9780718157838', 'Me Before You', 'Jojo Moyes', '2012', 'ouisa Clark takes a job caring for Will Traynor, a once-active man left paralyzed after an accident. Their relationship blossoms in unexpected ways, teaching them both about love, courage, and the value of life even when the odds seem impossible.', '67f225a3d8c8e_11.jpg', 2, 2, 13, 1, 2);

-- Dumping structure for table library_db.borrow
DROP TABLE IF EXISTS `borrow`;
CREATE TABLE IF NOT EXISTS `borrow` (
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

-- Dumping data for table library_db.borrow: ~0 rows (approximately)
INSERT INTO `borrow` (`borrow_id`, `borrow_date`, `due_date`, `return_date`, `borrow_book_id`, `borrow_member_id`) VALUES
	(35, '2025-04-04', '2025-04-04', '2025-04-06', 'B-000007', 1),
	(36, '2025-04-07', '2025-04-21', NULL, 'B-000001', 1),
	(37, '2025-04-06', '2025-04-20', NULL, 'B-000009', 1);

-- Dumping structure for table library_db.category
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(45) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.category: ~8 rows (approximately)
INSERT INTO `category` (`category_id`, `category_name`) VALUES
	(1, 'Fiction'),
	(2, 'Philosophy'),
	(3, 'Literature'),
	(4, 'Poetry'),
	(6, 'Thriller'),
	(7, 'Fantacy'),
	(12, 'Comic'),
	(13, 'Romance');

-- Dumping structure for table library_db.fines
DROP TABLE IF EXISTS `fines`;
CREATE TABLE IF NOT EXISTS `fines` (
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

-- Dumping data for table library_db.fines: ~1 rows (approximately)
INSERT INTO `fines` (`fine_id`, `amount`, `fine_borrow_id`, `fine_member_id`) VALUES
	(9, 10, 35, 1);

-- Dumping structure for table library_db.language
DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `language_id` int NOT NULL AUTO_INCREMENT,
  `language_name` varchar(45) NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.language: ~4 rows (approximately)
INSERT INTO `language` (`language_id`, `language_name`) VALUES
	(1, 'Sihnala'),
	(2, 'English'),
	(3, 'Tamil'),
	(4, 'Other');

-- Dumping structure for table library_db.library_info
DROP TABLE IF EXISTS `library_info`;
CREATE TABLE IF NOT EXISTS `library_info` (
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

-- Dumping data for table library_db.library_info: ~1 rows (approximately)
INSERT INTO `library_info` (`id`, `name`, `logo`, `address`, `mobile`, `email`, `membership_fee`, `fine_amount`) VALUES
	(1, 'SHELF LOOM', 'logo.png', 'University of Sri Jayewardenepura, Gangodawila, Nugegoda', '0111234567', 'dulakshigamma@gmail.com', 1000, 5);

-- Dumping structure for table library_db.member
DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
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

-- Dumping data for table library_db.member: ~2 rows (approximately)
INSERT INTO `member` (`id`, `nic`, `fname`, `lname`, `mobile`, `address`, `email`, `date_joined`, `vcode`, `profile_img`, `status_id`) VALUES
	(1, '200180300611', 'Kamal', 'Rathnayake', '0704567123', 'No, 134, cross road, Kurunagala', 'dulakshigamma@gmail.com', '2025-03-07', NULL, '67c91b567efe0_download.jpeg', 1),
	(19, '200178377654', 'mm', 'll', '0711232124', 'mmddd', 'dulakshmma@gmail.com', '2025-04-04', NULL, NULL, 1);

-- Dumping structure for table library_db.member_login
DROP TABLE IF EXISTS `member_login`;
CREATE TABLE IF NOT EXISTS `member_login` (
  `member_login_id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `memberId` int NOT NULL,
  PRIMARY KEY (`member_login_id`),
  UNIQUE KEY `member_id_UNIQUE` (`member_id`),
  KEY `fk_member_login_member1_idx` (`memberId`),
  CONSTRAINT `fk_member_login_member1` FOREIGN KEY (`memberId`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.member_login: ~1 rows (approximately)
INSERT INTO `member_login` (`member_login_id`, `member_id`, `password`, `memberId`) VALUES
	(1, 'M-000001', 'Dcg$11029', 1),
	(26, 'M-000002', ']*W2o}J<i<&F', 19);

-- Dumping structure for table library_db.member_saved_book
DROP TABLE IF EXISTS `member_saved_book`;
CREATE TABLE IF NOT EXISTS `member_saved_book` (
  `save_id` int NOT NULL AUTO_INCREMENT,
  `saved_member_id` int NOT NULL,
  `saved_book_id` varchar(12) NOT NULL,
  PRIMARY KEY (`save_id`,`saved_member_id`,`saved_book_id`),
  KEY `fk_member_has_book_book1_idx` (`saved_book_id`),
  KEY `fk_member_has_book_member1_idx` (`saved_member_id`),
  CONSTRAINT `fk_member_has_book_book1` FOREIGN KEY (`saved_book_id`) REFERENCES `book` (`book_id`),
  CONSTRAINT `fk_member_has_book_member1` FOREIGN KEY (`saved_member_id`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.member_saved_book: ~2 rows (approximately)
INSERT INTO `member_saved_book` (`save_id`, `saved_member_id`, `saved_book_id`) VALUES
	(18, 1, 'B-000005');

-- Dumping structure for table library_db.module
DROP TABLE IF EXISTS `module`;
CREATE TABLE IF NOT EXISTS `module` (
  `module_id` int NOT NULL AUTO_INCREMENT,
  `module_name` varchar(45) NOT NULL,
  `module_icon` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.module: ~5 rows (approximately)
INSERT INTO `module` (`module_id`, `module_name`, `module_icon`) VALUES
	(1, 'Staff Management', 'man.png'),
	(2, 'Book Management', 'reading-book.png'),
	(3, 'Circulation Management', 'book.png'),
	(4, 'Member Management', 'team.png'),
	(5, 'Payment Management', 'credit-card.png');

-- Dumping structure for table library_db.news
DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `image_path` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.news: ~1 rows (approximately)
INSERT INTO `news` (`id`, `title`, `date`, `description`, `image_path`) VALUES
	(1, 'New Book Arrived', '2025-03-28', 'The road by cormac maccarthy was arrived. Explore our collection for more actions.', 'box1.jpg');

-- Dumping structure for table library_db.notification
DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `status` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL,
  `receiver_id` varchar(45) NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.notification: ~8 rows (approximately)
INSERT INTO `notification` (`notification_id`, `message`, `status`, `created_at`, `receiver_id`) VALUES
	(1, 'dd', 'read', '2025-04-01 17:32:22', 'M-000001'),
	(2, 'Welcome to Shelf Loom!', 'read', '2025-04-01 17:56:09', 'M-000001'),
	(3, 'Your membership will expire soon.', 'read', '2025-04-01 17:56:09', 'M-000001'),
	(4, 'A new book has been added!', 'read', '2025-04-01 17:56:09', 'M-000001'),
	(5, '$subject', 'read', '2025-04-03 05:14:14', 'M-000001'),
	(6, '$subject', 'read', '2025-04-03 07:02:44', 'M-000001'),
	(7, 'fffffff', 'read', '2025-04-03 07:33:49', 'M-000001'),
	(8, 'Send Mail', 'unread', '2025-04-03 17:05:28', 'M-000001'),
	(9, 'd', 'unread', '2025-04-06 16:19:09', 'M-000001'),
	(10, 'Send Mail', 'unread', '2025-04-06 16:21:59', 'M-000001');

-- Dumping structure for table library_db.opening_hours
DROP TABLE IF EXISTS `opening_hours`;
CREATE TABLE IF NOT EXISTS `opening_hours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `day` varchar(45) NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.opening_hours: ~3 rows (approximately)
INSERT INTO `opening_hours` (`id`, `day`, `open_time`, `close_time`) VALUES
	(1, 'Week Day', '08:00:00', '17:00:00'),
	(2, 'Week End', '08:00:00', '14:00:00'),
	(3, 'Holiday', '00:00:00', '00:00:00');

-- Dumping structure for table library_db.payment
DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
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

-- Dumping data for table library_db.payment: ~0 rows (approximately)

-- Dumping structure for table library_db.reservation
DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
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

-- Dumping data for table library_db.reservation: ~2 rows (approximately)
INSERT INTO `reservation` (`reservation_id`, `reservation_date`, `reservation_member_id`, `reservation_book_id`, `status_id`, `notified_date`, `expiration_date`) VALUES
	(47, '2025-04-04', 1, 'B-000009', 2, NULL, '2025-04-11'),
	(48, '2025-04-04', 1, 'B-000007', 4, NULL, '2025-04-11');

-- Dumping structure for table library_db.reservation_status
DROP TABLE IF EXISTS `reservation_status`;
CREATE TABLE IF NOT EXISTS `reservation_status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.reservation_status: ~5 rows (approximately)
INSERT INTO `reservation_status` (`status_id`, `status`) VALUES
	(1, 'Reserved'),
	(2, 'Collected'),
	(3, 'Expired'),
	(4, 'Canceled'),
	(5, 'Waitlist');

-- Dumping structure for table library_db.role
DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.role: ~2 rows (approximately)
INSERT INTO `role` (`role_id`, `role_name`) VALUES
	(1, 'Librarian'),
	(2, 'Book Keeper');

-- Dumping structure for table library_db.role_has_module
DROP TABLE IF EXISTS `role_has_module`;
CREATE TABLE IF NOT EXISTS `role_has_module` (
  `role_id` int NOT NULL,
  `module_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`module_id`),
  KEY `fk_role_has_module_module1_idx` (`module_id`),
  KEY `fk_role_has_module_role1_idx` (`role_id`),
  CONSTRAINT `fk_role_has_module_module1` FOREIGN KEY (`module_id`) REFERENCES `module` (`module_id`),
  CONSTRAINT `fk_role_has_module_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.role_has_module: ~9 rows (approximately)
INSERT INTO `role_has_module` (`role_id`, `module_id`) VALUES
	(1, 1),
	(1, 2),
	(2, 2),
	(1, 3),
	(2, 3),
	(1, 4),
	(2, 4),
	(1, 5),
	(2, 5);

-- Dumping structure for table library_db.staff
DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
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

-- Dumping data for table library_db.staff: ~2 rows (approximately)
INSERT INTO `staff` (`id`, `nic`, `fname`, `lname`, `mobile`, `address`, `email`, `vcode`, `profile_img`, `role_id`, `status_id`) VALUES
	(1, '200180400345', 'Doe', 'John', '0701234567', 'ss', 'ss@gmail.com', NULL, '67f2190a8c800_download.jpeg', 1, 1),
	(2, '200180300611', 'Test1', 'Test2', '0704567123', 'Main road, Kandy', 'dulakshigamma@gmail.com', '67f2106d17500', NULL, 2, 1);

-- Dumping structure for table library_db.staff_key
DROP TABLE IF EXISTS `staff_key`;
CREATE TABLE IF NOT EXISTS `staff_key` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `key_value` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_value_UNIQUE` (`key_value`),
  KEY `fk_staff_key_role1_idx` (`role_id`),
  CONSTRAINT `fk_staff_key_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.staff_key: ~3 rows (approximately)
INSERT INTO `staff_key` (`id`, `email`, `key_value`, `role_id`) VALUES
	(3, 'dulakshigamma@gmail.com', 'B06F220789EDCD8FC6B95A7DCB7C3EC5', 2),
	(4, '', 'E1D9D36E0EDD55CC70149B51569BFA77', 2),
	(5, '', '54EBB20E4B8DB8B4BF97DE18AF6C5FFE', 2);

-- Dumping structure for table library_db.staff_login
DROP TABLE IF EXISTS `staff_login`;
CREATE TABLE IF NOT EXISTS `staff_login` (
  `login_id` int NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `staffId` int NOT NULL,
  PRIMARY KEY (`login_id`),
  KEY `fk_login_user1_idx` (`staffId`),
  CONSTRAINT `fk_login_user1` FOREIGN KEY (`staffId`) REFERENCES `staff` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.staff_login: ~1 rows (approximately)
INSERT INTO `staff_login` (`login_id`, `staff_id`, `password`, `staffId`) VALUES
	(1, 'S-000001', 'Dcg$11029', 1),
	(2, 'S-000002', 'Asd!1234', 2);

-- Dumping structure for table library_db.status
DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library_db.status: ~5 rows (approximately)
INSERT INTO `status` (`status_id`, `status`) VALUES
	(1, 'Active'),
	(2, 'Deactive'),
	(3, 'Pending'),
	(4, 'Rejected'),
	(5, 'Expired');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
