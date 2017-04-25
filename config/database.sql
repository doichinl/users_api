CREATE DATABASE  IF NOT EXISTS `users` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `users`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `forename` varchar(255) NOT NULL DEFAULT '',
  `surname` varchar(255) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES 
    (1,'ivan@peshov.com','Ivan','Peshov','2017-04-25 10:10:06'),
    (2,'plamen@vasilev.com','Plamen','Valisev','2017-04-25 10:07:03'),
    (3,'petar@petrov.com','Petar','Petrov','2017-04-25 10:09:03'),
    (4,'ivan@petrov.com','Ivan','Petrov','2017-04-25 10:11:08');
UNLOCK TABLES;