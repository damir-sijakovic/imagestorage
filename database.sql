/* dnd into phpmyadmin */

DROP DATABASE IF EXISTS `dsImageStorage`;
CREATE DATABASE `dsImageStorage` DEFAULT CHARSET=utf8;
DROP USER IF EXISTS 'dsImageStorage'@'localhost';
CREATE USER 'dsImageStorage'@'localhost' IDENTIFIED BY 'dsImageStorage';
GRANT ALL ON `dsImageStorage`.* TO 'dsImageStorage'@'localhost';
USE `dsImageStorage`;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(48) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` DATETIME DEFAULT NOW(),  
  PRIMARY KEY (`id`),
  CONSTRAINT uc_users_name UNIQUE (`name`)
) ENGINE=InnoDB;


DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `type` VARCHAR(3) NOT NULL,
  `size` INTEGER NOT NULL, /* up to 2GB */
  `user_id` INTEGER NOT NULL,
  FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

