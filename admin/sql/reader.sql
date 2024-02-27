CREATE USER 'reader'@'localhost' IDENTIFIED BY 'pw';
GRANT SELECT ON *.* TO 'reader'@'localhost';
FLUSH PRIVILEGES;

/*!40000 ALTER TABLE `information` DISABLE KEYS */;
INSERT INTO `information` (`name`, `value`) VALUES
    ('user:3', 'reader:pw');
/*!40000 ALTER TABLE `information` ENABLE KEYS */;