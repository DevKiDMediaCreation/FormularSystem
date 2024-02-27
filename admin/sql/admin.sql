CREATE USER 'admin'@'localhost' IDENTIFIED BY 'pw';
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;

/*!40000 ALTER TABLE `information` DISABLE KEYS */;
INSERT INTO `information` (`name`, `value`) VALUES
                                                ('user:1', 'admin:pw');
/*!40000 ALTER TABLE `information` ENABLE KEYS */;