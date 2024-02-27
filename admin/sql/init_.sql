-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               8.3.0 - MySQL Community Server - GPL
-- Server Betriebssystem:        Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Exportiere Struktur von Tabelle feedbacksys.data
CREATE TABLE IF NOT EXISTS `data` (
                                      `id` int NOT NULL,
                                      `question` text COLLATE utf8mb4_general_ci NOT NULL,
                                      `answer` text COLLATE utf8mb4_general_ci,
                                      `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `userid` int NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `token` (`token`),
    KEY `user_id` (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle feedbacksys.form
CREATE TABLE IF NOT EXISTS `form` (
                                      `id` int NOT NULL AUTO_INCREMENT,
                                      `tokens` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
    `form_file` text COLLATE utf8mb4_general_ci NOT NULL,
    `formularid` int NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `tokens` (`tokens`),
    KEY `FK_form_formular` (`formularid`),
    CONSTRAINT `FK_form_formular` FOREIGN KEY (`formularid`) REFERENCES `formular` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle feedbacksys.formular
CREATE TABLE IF NOT EXISTS `formular` (
                                          `id` int NOT NULL AUTO_INCREMENT,
                                          `question` text COLLATE utf8mb4_general_ci NOT NULL,
                                          `answertype` enum('text','number') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'text',
    `group` int NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle feedbacksys.tokens
CREATE TABLE IF NOT EXISTS `tokens` (
                                        `id` int NOT NULL AUTO_INCREMENT,
                                        `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `userid` int NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `token` (`token`),
    KEY `userid` (`userid`),
    CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES `users` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle feedbacksys.users
CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int NOT NULL AUTO_INCREMENT,
                                       `user` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                                       `pw` text COLLATE utf8mb4_general_ci NOT NULL,
                                       `rights` int NOT NULL DEFAULT '4',
                                       `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Daten Export vom Benutzer nicht ausgewählt

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
