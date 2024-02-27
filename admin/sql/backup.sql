CREATE USER 'backup'@'localhost' IDENTIFIED BY 'pw';
GRANT SELECT, LOCK TABLES ON *.* TO 'backup'@'localhost';
FLUSH PRIVILEGES;

/*!40000 ALTER TABLE `information` DISABLE KEYS */;
INSERT INTO `information` (`name`, `value`) VALUES
    ('user:2', 'backup:pw');
/*!40000 ALTER TABLE `information` ENABLE KEYS */;