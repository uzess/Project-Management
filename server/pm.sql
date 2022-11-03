/*
SQLyog Community v11.31 (64 bit)
MySQL - 5.6.17 : Database - project-management
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`project-management` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `project-management`;

/*Table structure for table `user_roles` */

DROP TABLE IF EXISTS `user_roles`;

CREATE TABLE `user_roles` (
  `ID` bigint(20) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  KEY `FK_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_roles` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `password` text,
  `update_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`ID`,`first_name`,`last_name`,`password`,`update_on`,`created_on`) values (1,'abiral','gai','aiadsfa','2015-12-07 14:25:10','2015-12-07 14:25:17'),(2,'yujesh','shrestha','sdfasdaksdjfas\r\n','2015-12-06 14:26:22','2015-12-06 14:26:25');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
