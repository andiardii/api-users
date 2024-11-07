-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for dlab
CREATE DATABASE IF NOT EXISTS `dlab` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dlab`;

-- Dumping structure for table dlab.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `umur` int NOT NULL,
  `role` int NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `FK_users_user_role` (`role`),
  CONSTRAINT `FK_users_user_role` FOREIGN KEY (`role`) REFERENCES `user_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dlab.users: ~14 rows (approximately)
INSERT INTO `users` (`id`, `nama`, `email`, `umur`, `role`, `password`) VALUES
	(1, 'admin', 'admin@example.com', 30, 1, '$2y$12$lZEcXisH16sljJH/PwaqyOIMEH7NH/CNC6tzSZs95i1ozJoO8ok8O'),
	(2, 'mod', 'mod@example.com', 27, 2, '$2y$12$lZEcXisH16sljJH/PwaqyOIMEH7NH/CNC6tzSZs95i1ozJoO8ok8O'),
	(3, 'staff', 'staff@example.com', 21, 3, '$2y$12$lZEcXisH16sljJH/PwaqyOIMEH7NH/CNC6tzSZs95i1ozJoO8ok8O'),
	(11, 'Staff_2', 'staff_2@example.com', 18, 3, '$2y$12$/ahDehN7VCrCRDOQfdpeiOUb3vtj/1eYto7GJkRYZnPaOBJ2Bl6f6'),
	(12, 'Staff_0', 'staff_0@example.com', 21, 3, '$2y$12$KAkCe05KNxwA7qEMoheOf.SEtt8g0y1ar8fsIxBiDpRyD4X2PCCbW'),
	(13, 'Staff_3', 'staff_3@example.com', 23, 3, '$2y$12$t0g/yhz2i5S11bQZ3CiknubIAzKu/cdcLQfTwOgswhbxxg93K6zkS'),
	(15, 'Staff_5', 'staff_5@example.com', 27, 3, '$2y$12$0.Us7Yp9CVdSPkp5hcN7We7JUfQBOUYKN4GTRHmKLa8fDnf5gTqAO'),
	(16, 'Staff_6', 'staff_6@example.com', 20, 3, '$2y$12$9IEZSwhsU/fp4G2augORB.Fzz2WxGBgXr7y5wY6aFZM6qoaLKb/Iy'),
	(17, 'Staff_7', 'staff_7@example.com', 22, 3, '$2y$12$tu5e1thcbM5czyv5juWVae.qvTj.FoKrjWLBQaP8MOGjBxVgCGY9q'),
	(18, 'Staff_8', 'staff_8@example.com', 24, 3, '$2y$12$CH9NDKxgElfe.3VFT7dPUO8/ShY9VwQck3nyvEwhaAARZ3sBKTsR.'),
	(19, 'Staff_9', 'staff_9@example.com', 25, 3, '$2y$12$pVk1gR9hErskZ.UPdqUneeWbwvR4Cm09rSNMVicdB/iMOn2/tJNiW'),
	(26, 'John Doe', 'example@example.com', 26, 3, '$2y$12$ba0jQ7VNO/GnMoRe8IMzieI93JfvUCmkjSX6r9i7BRM5hcA6Qh8zO'),
	(27, 'John Wick', 'wick@example.com', 28, 3, '$2y$12$PU8YyzbIPMvtPNSm0kAkf.vmVEga5fGsfYm/3AqyGn05NrA.Sov8W'),
	(28, 'Delete', 'delete@example.com', 28, 3, '$2y$12$PU8YyzbIPMvtPNSm0kAkf.vmVEga5fGsfYm/3AqyGn05NrA.Sov8W');

-- Dumping structure for table dlab.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `roles` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dlab.user_role: ~3 rows (approximately)
INSERT INTO `user_role` (`id`, `roles`) VALUES
	(1, 'admin'),
	(2, 'moderator'),
	(3, 'member');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
