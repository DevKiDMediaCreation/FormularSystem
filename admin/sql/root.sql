CREATE USER 'root'@'localhost' IDENTIFIED BY 'pw';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost';
FLUSH PRIVILEGES;

/*!40000 ALTER TABLE `information` DISABLE KEYS */;
INSERT INTO `information` (`name`, `value`) VALUES
    ('user:0', 'root:pw');
/*!40000 ALTER TABLE `information` ENABLE KEYS */;