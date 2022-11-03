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

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`id`,`ip_address`,`timestamp`,`data`) values ('7bc0cbbf11647175eed26e97cc516e829ee0bc1a','192.168.2.37',1455602705,'__ci_last_regenerate|i:1455602704;'),('6976c96a9127f99414f6d7cbebd66ba3c223c47b','192.168.2.37',1455602790,'__ci_last_regenerate|i:1455602724;user_id|s:3:\"107\";username|s:5:\"admin\";first_name|s:14:\"Administrators\";last_name|s:10:\"Shresthass\";role|s:5:\"Super\";role_id|s:2:\"21\";'),('626fa057b213e40a6d79bc6fd282384dd1540b4a','192.168.2.37',1455603384,'__ci_last_regenerate|i:1455603188;user_id|s:3:\"107\";username|s:5:\"admin\";first_name|s:14:\"Administrators\";last_name|s:10:\"Shresthass\";role|s:5:\"Super\";role_id|s:2:\"21\";'),('50c81603700a8b70355bc02ce7dd7347c6ef688e','192.168.2.37',1455604083,'__ci_last_regenerate|i:1455604039;user_id|s:3:\"107\";username|s:5:\"admin\";first_name|s:14:\"Administrators\";last_name|s:10:\"Shresthass\";role|s:5:\"Super\";role_id|s:2:\"21\";'),('981d5609d0fed57b23e0217c960ba88a52e0b631','192.168.2.37',1455605164,'__ci_last_regenerate|i:1455605163;user_id|s:3:\"107\";username|s:5:\"admin\";first_name|s:14:\"Administrators\";last_name|s:10:\"Shresthass\";role|s:5:\"Super\";role_id|s:2:\"21\";'),('7cdb148bafe6d1ed78b23230cfe8b02ab2deeaff','192.168.2.37',1455606394,'__ci_last_regenerate|i:1455606388;user_id|s:3:\"107\";username|s:5:\"admin\";first_name|s:14:\"Administrators\";last_name|s:10:\"Shresthass\";role|s:5:\"Super\";role_id|s:2:\"21\";'),('80b8ccf4f75bd8687c714d8f7248159c17167262','192.168.2.37',1455607302,'__ci_last_regenerate|i:1455607293;user_id|s:3:\"107\";username|s:5:\"admin\";first_name|s:14:\"Administrators\";last_name|s:10:\"Shresthass\";role|s:5:\"Super\";role_id|s:2:\"21\";'),('31ea219b7b5695b664085fa217ec34fddcec368e','192.168.2.37',1455608043,'__ci_last_regenerate|i:1455608043;user_id|s:3:\"107\";username|s:5:\"admin\";first_name|s:14:\"Administrators\";last_name|s:10:\"Shresthass\";role|s:5:\"Super\";role_id|s:2:\"21\";'),('00a5dbd388a843c32507c70fecef23e5bafb2947','192.168.2.37',1455609162,'__ci_last_regenerate|i:1455609157;user_id|s:3:\"107\";username|s:5:\"admin\";first_name|s:14:\"Administrators\";last_name|s:10:\"Shresthass\";role|s:5:\"Super\";role_id|s:2:\"21\";');

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `comment` text,
  `parent_id` bigint(20) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_projects_ID_comments` (`project_id`),
  KEY `FK_users_ID_comments` (`user_id`),
  CONSTRAINT `FK_projects_ID_comments` FOREIGN KEY (`project_id`) REFERENCES `projects` (`ID`),
  CONSTRAINT `FK_users_ID_comments` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `comments` */

/*Table structure for table `estimations` */

DROP TABLE IF EXISTS `estimations`;

CREATE TABLE `estimations` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `status` varchar(50) DEFAULT NULL,
  `total_period` varchar(100) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_on` varchar(100) DEFAULT NULL,
  `updated_on` varchar(100) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_users.ID` (`user_id`),
  CONSTRAINT `FK_users.ID` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

/*Data for the table `estimations` */

insert  into `estimations`(`ID`,`name`,`description`,`status`,`total_period`,`user_id`,`created_on`,`updated_on`,`created_date`) values (2,'Project Management System','Project Management System','rejected','77',97,'1453268538','1453268538','2016-01-21'),(3,'Estimation - two','Estimation - two','accepted','32',97,'1453268538','1454337672','2016-01-22'),(4,'Poker','Poker','accepted','87',107,'1453268538','1453376259','2016-01-23'),(23,'New Estimation','kk','pending',NULL,107,'1453270600','1453376264','2016-01-23'),(24,'new',NULL,'pending',NULL,107,'1453989492','1453989492','2016-01-28'),(25,'fads',NULL,'pending',NULL,107,'1453989597','1453989597','2016-01-28'),(26,'Aboll','horseeeeeee','pending','3',107,'1453989894','1454337697','2016-01-28'),(27,'Infraxis','Hello Infraxis','accepted','5',107,'1454336807','1454336807','2015-02-01');

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `permit_code` text,
  `parent_id` bigint(20) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `icon` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

/*Data for the table `menu` */

insert  into `menu`(`ID`,`name`,`slug`,`permit_code`,`parent_id`,`updated_on`,`created_on`,`weight`,`icon`) values (1,'Dashboard','#/dashboard','dashboard',0,NULL,NULL,0,'fa-dashboard'),(3,'All Users','#/user','user',34,NULL,NULL,0,'fa fa-user'),(4,'Roles','#/role','role',34,NULL,NULL,10,'fa fa-users'),(6,'Skills','#/skill','skill',34,NULL,NULL,20,'fa fa-pencil-square-o'),(7,'DB Migrate','#/dbMigrate','db-migrate',13,NULL,NULL,10,'fa fa-cog'),(9,'Settings','javascript:void(0)','setup',100,NULL,NULL,10,'fa fa-cogs'),(13,'Utilities','javascript:void(0)','utilities',0,NULL,NULL,50,'fa fa-cogs'),(14,'Create','user-create','user-create',3,NULL,NULL,-1,NULL),(15,'Update','user-update','user-update',3,NULL,NULL,-1,NULL),(16,'Delete','user-delete','user-delete',3,NULL,NULL,-1,NULL),(17,'Create','role-create','role-create',4,NULL,NULL,-1,NULL),(18,'Update','role-update','role-update',4,NULL,NULL,-1,NULL),(19,'Delete','role-delete','role-delete',4,NULL,NULL,-1,NULL),(20,'Create','skill-create','skill-create',6,NULL,NULL,-1,NULL),(21,'Update','skill-update','skill-update',6,NULL,NULL,-1,NULL),(22,'Delete','skill-delete','skill-delete',6,NULL,NULL,-1,NULL),(23,'Estimations','#/estimation','estimation\r\n',0,NULL,NULL,30,'fa fa-cogs\r\n'),(24,'Create','estimation-create','estimation-create\r\n',23,NULL,NULL,-1,NULL),(25,'Update','estimation-update','estimation-update',23,NULL,NULL,-1,NULL),(26,'Delete','estimation-delete','estimation-delete\r\n',23,NULL,NULL,-1,NULL),(27,'Projects','#/project','project',0,NULL,NULL,20,'fa fa-cogs'),(28,'Create','project-create','project-create',27,NULL,NULL,-1,NULL),(29,'Update','project-update','project-update',27,NULL,NULL,-1,NULL),(30,'Delete','project-delete','project-delete',27,NULL,NULL,-1,NULL),(31,'Projects','javascript:void(0)','#	',100,NULL,NULL,30,'fa fa-dashboard'),(32,'Settings','#/settings','settings',0,NULL,NULL,60,'fa fa-cogs'),(33,'Create','settings-update','settings-update',32,NULL,NULL,-1,NULL),(34,'Users','javascript:void(0)','users',0,NULL,NULL,40,'fa fa-users');

/*Table structure for table `project_user` */

DROP TABLE IF EXISTS `project_user`;

CREATE TABLE `project_user` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=422 DEFAULT CHARSET=latin1;

/*Data for the table `project_user` */

insert  into `project_user`(`ID`,`project_id`,`user_id`,`created_on`,`updated_on`) values (209,42,117,'2016-01-17 16:47:01','2016-01-17 16:47:01'),(210,42,111,'2016-01-17 16:47:01','2016-01-17 16:47:01'),(211,42,116,'2016-01-17 16:47:01','2016-01-17 16:47:01'),(212,43,117,'2016-01-17 16:47:11','2016-01-17 16:47:11'),(213,43,111,'2016-01-17 16:47:11','2016-01-17 16:47:11'),(214,43,116,'2016-01-17 16:47:11','2016-01-17 16:47:11'),(215,44,117,'2016-01-17 16:47:21','2016-01-17 16:47:21'),(216,44,111,'2016-01-17 16:47:21','2016-01-17 16:47:21'),(217,44,116,'2016-01-17 16:47:21','2016-01-17 16:47:21'),(218,45,117,'2016-01-17 16:51:05','2016-01-17 16:51:05'),(219,45,111,'2016-01-17 16:51:05','2016-01-17 16:51:05'),(220,45,116,'2016-01-17 16:51:05','2016-01-17 16:51:05'),(221,46,117,'2016-01-17 17:09:41','2016-01-17 17:09:41'),(222,46,111,'2016-01-17 17:09:41','2016-01-17 17:09:41'),(223,46,116,'2016-01-17 17:09:41','2016-01-17 17:09:41'),(227,49,111,'2016-01-17 17:12:22','2016-01-17 17:12:22'),(228,50,111,'2016-01-17 17:12:33','2016-01-17 17:12:33'),(229,51,111,'2016-01-17 17:14:42','2016-01-17 17:14:42'),(230,52,111,'2016-01-17 17:15:28','2016-01-17 17:15:28'),(266,61,109,'2016-01-18 15:42:02','2016-01-18 15:42:02'),(267,61,112,'2016-01-18 15:42:02','2016-01-18 15:42:02'),(268,61,115,'2016-01-18 15:42:02','2016-01-18 15:42:02'),(281,57,116,'2016-01-18 16:55:08','2016-01-18 16:55:08'),(283,55,120,'2016-01-18 16:55:21','2016-01-18 16:55:21'),(284,55,121,'2016-01-18 16:55:21','2016-01-18 16:55:21'),(285,54,110,'2016-01-18 16:55:26','2016-01-18 16:55:26'),(286,54,112,'2016-01-18 16:55:26','2016-01-18 16:55:26'),(287,53,117,'2016-01-18 16:55:34','2016-01-18 16:55:34'),(288,53,111,'2016-01-18 16:55:34','2016-01-18 16:55:34'),(289,48,111,'2016-01-18 16:55:39','2016-01-18 16:55:39'),(290,47,121,'2016-01-18 16:55:43','2016-01-18 16:55:43'),(291,47,97,'2016-01-18 16:55:43','2016-01-18 16:55:43'),(295,39,110,'2016-01-18 16:55:56','2016-01-18 16:55:56'),(296,39,115,'2016-01-18 16:55:56','2016-01-18 16:55:56'),(304,26,97,'2016-01-18 16:56:12','2016-01-18 16:56:12'),(305,26,111,'2016-01-18 16:56:12','2016-01-18 16:56:12'),(306,26,110,'2016-01-18 16:56:12','2016-01-18 16:56:12'),(307,26,112,'2016-01-18 16:56:12','2016-01-18 16:56:12'),(308,26,115,'2016-01-18 16:56:12','2016-01-18 16:56:12'),(343,41,117,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(344,41,111,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(345,41,116,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(349,56,117,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(350,56,115,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(354,60,110,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(376,62,110,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(377,62,116,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(378,62,120,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(379,62,115,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(380,62,109,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(381,64,117,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(382,64,116,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(383,64,115,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(384,64,109,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(385,64,110,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(386,64,111,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(393,65,115,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(394,65,111,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(395,65,117,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(396,65,109,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(397,65,112,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(398,65,97,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(405,66,112,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(406,66,109,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(407,66,97,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(408,66,117,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(409,66,120,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(410,66,115,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(411,59,116,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(412,59,110,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(413,59,112,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(414,58,121,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(415,58,116,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(416,58,112,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(417,35,109,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(418,38,120,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(419,38,111,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(420,38,117,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(421,67,116,'0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `estimation_id` bigint(20) DEFAULT NULL,
  `started_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_estimation_ID` (`estimation_id`),
  CONSTRAINT `FK_estimation_ID` FOREIGN KEY (`estimation_id`) REFERENCES `estimations` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;

/*Data for the table `projects` */

insert  into `projects`(`ID`,`name`,`description`,`created_on`,`updated_on`,`status`,`estimation_id`,`started_date`,`completed_date`) values (26,'Project Management System','The system keeps tracking for each project assigned to the user.','2015-12-27 11:01:09','2016-01-18 16:56:12','ongoing',3,'2016-01-10','2016-01-11'),(35,'Ivar','Ivar world','2015-12-29 13:46:32','0000-00-00 00:00:00','postponed',2,'2016-01-11','2016-01-10'),(38,'Poker API',NULL,'2016-01-17 09:34:18','0000-00-00 00:00:00','ongoing',2,'2015-12-31','2016-01-20'),(39,'Jyovan Bhuju',NULL,'2016-01-17 11:42:58','2016-01-18 16:55:56','ongoing',3,'2015-12-31','2016-01-22'),(40,'Helo Pro','ello pro is hello pro','2016-01-17 14:09:51','2016-01-18 16:55:52','completed',2,'2015-12-31','2016-01-22'),(41,'Prem Dhoj Pradhan','Melody King','2016-01-17 16:46:52','0000-00-00 00:00:00','completed',3,'2016-01-16','2016-01-18'),(47,'Michael Jackson',NULL,'2016-01-17 17:10:46','2016-01-18 16:55:43','completed',3,'2015-12-31','2016-01-28'),(48,'Iron Maiden',NULL,'2016-01-17 17:12:19','2016-01-18 16:55:38','completed',2,'2015-12-31','2016-01-19'),(53,'Sapana Bhulai Sara','Rabi Hada','2016-01-17 17:16:29','2016-01-18 16:55:34','ongoing',3,'2016-01-06','2016-01-25'),(54,'Khai khai',NULL,'2016-01-17 19:49:45','2016-01-18 16:55:26','rejected',3,'2016-01-05','2015-12-31'),(55,'Bidhan shrestha',NULL,'2016-01-18 11:55:44','2016-01-18 16:55:21','ongoing',3,'2015-12-31','2016-01-18'),(56,'Biraj Shrestha',NULL,'2016-01-18 11:58:25','0000-00-00 00:00:00','ongoing',3,'2016-01-17','2016-01-05'),(57,'Lyasi Buda',NULL,'2016-01-18 11:59:58','2016-01-18 16:55:08','ongoing',3,'2016-01-18','2016-01-25'),(58,'Pratigya',NULL,'2016-01-18 12:05:29','0000-00-00 00:00:00','ongoing',2,'2016-01-10','2016-01-19'),(59,'Love is in the air','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2016-01-18 12:07:46','0000-00-00 00:00:00','completed',3,'2016-01-01','2016-01-17'),(60,'Okay thiks',NULL,'2016-01-18 12:08:09','0000-00-00 00:00:00','rejected',2,'2016-01-08','2016-01-04'),(61,'Dherai dherai maya',NULL,'2016-01-18 12:08:39','2016-01-18 15:42:02','completed',2,'2016-01-01','2016-01-04'),(62,'Mukti ko Ghar jam hola kidsss','Hello Mukti dai','2016-01-18 15:49:55','0000-00-00 00:00:00','postponed',3,'2016-01-10','2016-01-11'),(64,'Project Bam Bam','Bomer Boy Project','0000-00-00 00:00:00','0000-00-00 00:00:00','completed',3,'2015-12-31','2016-01-21'),(65,'Angular Project',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00','completed',3,'2015-12-31','2016-01-21'),(66,'Social Club','Social Club is Organizing Blood Donation Program on Comming Sunday.','0000-00-00 00:00:00','0000-00-00 00:00:00','ongoing',3,'2016-01-06','2016-01-29'),(67,'Naya Project',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00','ongoing',24,'2015-12-31','2016-01-13');

/*Table structure for table `role_menu` */

DROP TABLE IF EXISTS `role_menu`;

CREATE TABLE `role_menu` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_menu_ID` (`menu_id`),
  KEY `FK_role_ID` (`role_id`),
  CONSTRAINT `FK_menu_ID` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`ID`),
  CONSTRAINT `FK_role_ID` FOREIGN KEY (`role_id`) REFERENCES `roles` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=896 DEFAULT CHARSET=latin1;

/*Data for the table `role_menu` */

insert  into `role_menu`(`ID`,`menu_id`,`role_id`,`created_on`,`updated_on`) values (779,3,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(780,9,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(781,27,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(782,23,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(783,31,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(784,1,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(785,24,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(786,25,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(787,26,22,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(854,34,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(855,13,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(856,1,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(857,31,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(858,27,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(859,23,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(860,9,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(861,24,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(862,25,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(863,28,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(864,29,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(865,7,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(866,32,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(867,6,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(868,4,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(869,3,20,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(870,21,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(871,1,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(872,27,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(873,28,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(874,29,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(875,30,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(876,23,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(877,24,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(878,25,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(879,26,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(880,34,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(881,3,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(882,14,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(883,15,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(884,16,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(885,19,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(886,22,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(887,18,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(888,20,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(889,17,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(890,4,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(891,6,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(892,13,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(893,7,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(894,32,23,'0000-00-00 00:00:00','0000-00-00 00:00:00'),(895,33,23,'0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Data for the table `roles` */

insert  into `roles`(`ID`,`name`,`updated_on`,`created_on`) values (20,'Developer','0000-00-00 00:00:00','2015-12-22 20:21:52'),(21,'Super','2015-12-22 20:56:13','2015-12-22 20:56:18'),(22,'Designer','0000-00-00 00:00:00','2015-12-27 10:37:35'),(23,'Project Manager','0000-00-00 00:00:00','2016-01-20 09:38:11');

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) DEFAULT NULL,
  `value` text,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `settings` */

insert  into `settings`(`ID`,`key`,`value`,`created_on`,`updated_on`) values (1,'items_per_page','5','2016-01-17 15:55:45','0000-00-00 00:00:00'),(2,'site_title','Project Managementss','2016-01-17 19:20:16','0000-00-00 00:00:00');

/*Table structure for table `skills` */

DROP TABLE IF EXISTS `skills`;

CREATE TABLE `skills` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `skills` */

insert  into `skills`(`ID`,`name`,`created_on`,`updated_on`) values (1,'jQuery','2015-12-20 19:00:43','2015-12-20 19:43:00'),(8,'Python','2015-12-20 19:02:23','2015-12-20 19:42:33'),(9,'Javascript','2015-12-20 19:02:23','2015-12-20 19:42:20'),(10,'Php','2015-12-20 19:26:58','2015-12-20 19:42:08'),(12,'Angular JS','2015-12-20 19:43:12','2015-12-20 19:43:12'),(15,'Wordpressss','2015-12-20 19:44:55','2016-01-20 09:34:20'),(16,'CSS','2015-12-20 19:45:06','2015-12-20 19:45:06'),(17,'CodeIgniter','2015-12-20 19:45:25','2015-12-26 07:35:59');

/*Table structure for table `user_roles` */

DROP TABLE IF EXISTS `user_roles`;

CREATE TABLE `user_roles` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `user_roles` */

insert  into `user_roles`(`ID`,`name`,`updated_on`,`created_on`) values (7,'Administrator','2015-12-09 21:46:24','2015-12-09 21:46:24'),(8,'Editor','2015-12-09 21:46:33','2015-12-09 21:46:33'),(9,'Subscriber','2015-12-09 21:46:46','2015-12-09 21:46:46');

/*Table structure for table `user_skill` */

DROP TABLE IF EXISTS `user_skill`;

CREATE TABLE `user_skill` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `skill_id` bigint(20) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_user_id` (`user_id`),
  KEY `FK_skill_id` (`skill_id`),
  CONSTRAINT `FK_skill_id` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`ID`),
  CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=323 DEFAULT CHARSET=latin1;

/*Data for the table `user_skill` */

insert  into `user_skill`(`ID`,`user_id`,`skill_id`,`created_on`,`updated_on`,`level`) values (64,110,1,'2015-12-27 10:38:20','2015-12-27 10:38:20',1),(65,110,9,'2015-12-27 10:38:20','2015-12-27 10:38:20',6),(66,110,17,'2015-12-27 10:38:20','2015-12-27 10:38:20',1),(67,110,16,'2015-12-27 10:38:20','2015-12-27 10:38:20',2),(68,110,10,'2015-12-27 10:38:20','2015-12-27 10:38:20',3),(107,112,9,'2015-12-27 18:02:50','2015-12-27 18:02:50',2),(108,112,16,'2015-12-27 18:02:50','2015-12-27 18:02:50',3),(109,112,15,'2015-12-27 18:02:50','2015-12-27 18:02:50',4),(110,112,17,'2015-12-27 18:02:50','2015-12-27 18:02:50',5),(111,112,1,'2015-12-27 18:02:50','2015-12-27 18:02:50',6),(151,111,15,'2015-12-28 15:32:23','2015-12-28 15:32:23',4),(152,111,9,'2015-12-28 15:32:23','2015-12-28 15:32:23',3),(153,111,10,'2015-12-28 15:32:23','2015-12-28 15:32:23',5),(154,111,16,'2015-12-28 15:32:23','2015-12-28 15:32:23',7),(155,111,17,'2015-12-28 15:32:23','2015-12-28 15:32:23',5),(156,111,1,'2015-12-28 15:32:23','2015-12-28 15:32:23',7),(157,111,8,'2015-12-28 15:32:23','2015-12-28 15:32:23',9),(158,115,16,'2016-01-12 17:08:24','2016-01-12 17:08:24',5),(159,115,15,'2016-01-12 17:08:24','2016-01-12 17:08:24',3),(160,115,10,'2016-01-12 17:08:24','2016-01-12 17:08:24',7),(161,115,9,'2016-01-12 17:08:24','2016-01-12 17:08:24',9),(162,115,1,'2016-01-12 17:08:24','2016-01-12 17:08:24',5),(163,116,17,'2016-01-14 16:21:29','2016-01-14 16:21:29',3),(164,116,9,'2016-01-14 16:21:30','2016-01-14 16:21:30',5),(165,116,8,'2016-01-14 16:21:30','2016-01-14 16:21:30',7),(166,116,15,'2016-01-14 16:21:30','2016-01-14 16:21:30',9),(167,116,1,'2016-01-14 16:21:30','2016-01-14 16:21:30',5),(168,116,12,'2016-01-14 16:21:30','2016-01-14 16:21:30',4),(169,116,10,'2016-01-14 16:21:30','2016-01-14 16:21:30',6),(170,117,17,'2016-01-14 16:22:39','2016-01-14 16:22:39',8),(171,117,9,'2016-01-14 16:22:39','2016-01-14 16:22:39',5),(172,117,8,'2016-01-14 16:22:39','2016-01-14 16:22:39',5),(173,117,16,'2016-01-14 16:22:39','2016-01-14 16:22:39',7),(174,117,15,'2016-01-14 16:22:39','2016-01-14 16:22:39',5),(175,117,1,'2016-01-14 16:22:40','2016-01-14 16:22:40',9),(176,117,12,'2016-01-14 16:22:40','2016-01-14 16:22:40',6),(177,117,10,'2016-01-14 16:22:40','2016-01-14 16:22:40',4),(184,121,17,'2016-01-14 16:25:11','2016-01-14 16:25:11',8),(185,121,8,'2016-01-14 16:25:11','2016-01-14 16:25:11',9),(186,121,15,'2016-01-14 16:25:11','2016-01-14 16:25:11',0),(189,120,10,'2016-01-20 09:40:10','2016-01-20 09:40:10',8),(190,120,1,'2016-01-20 09:40:10','2016-01-20 09:40:10',7),(191,120,15,'2016-01-20 09:40:10','2016-01-20 09:40:10',6),(192,120,16,'2016-01-20 09:40:10','2016-01-20 09:40:10',5),(193,120,8,'2016-01-20 09:40:10','2016-01-20 09:40:10',4),(194,120,9,'2016-01-20 09:40:10','2016-01-20 09:40:10',4),(195,120,17,'2016-01-20 09:40:10','2016-01-20 09:40:10',3),(288,107,17,'0000-00-00 00:00:00','0000-00-00 00:00:00',2),(289,107,16,'0000-00-00 00:00:00','0000-00-00 00:00:00',1),(290,107,15,'0000-00-00 00:00:00','0000-00-00 00:00:00',2),(291,107,12,'0000-00-00 00:00:00','0000-00-00 00:00:00',3),(306,109,1,'0000-00-00 00:00:00','0000-00-00 00:00:00',4),(307,109,9,'0000-00-00 00:00:00','0000-00-00 00:00:00',5),(308,109,10,'0000-00-00 00:00:00','0000-00-00 00:00:00',6),(309,109,12,'0000-00-00 00:00:00','0000-00-00 00:00:00',7),(310,109,15,'0000-00-00 00:00:00','0000-00-00 00:00:00',8),(311,109,16,'0000-00-00 00:00:00','0000-00-00 00:00:00',7),(312,109,17,'0000-00-00 00:00:00','0000-00-00 00:00:00',6),(313,97,17,'0000-00-00 00:00:00','0000-00-00 00:00:00',5),(314,97,16,'0000-00-00 00:00:00','0000-00-00 00:00:00',3),(315,97,1,'0000-00-00 00:00:00','0000-00-00 00:00:00',7),(316,97,8,'0000-00-00 00:00:00','0000-00-00 00:00:00',8),(317,97,9,'0000-00-00 00:00:00','0000-00-00 00:00:00',8),(318,97,12,'0000-00-00 00:00:00','0000-00-00 00:00:00',9),(319,97,15,'0000-00-00 00:00:00','0000-00-00 00:00:00',7),(320,97,10,'0000-00-00 00:00:00','0000-00-00 00:00:00',6),(321,122,16,'0000-00-00 00:00:00','0000-00-00 00:00:00',6),(322,122,17,'0000-00-00 00:00:00','0000-00-00 00:00:00',5);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `password` text,
  `user_role_id` bigint(20) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `user_unique_key` (`username`),
  KEY `FK_user_roles_ID` (`user_role_id`),
  CONSTRAINT `FK_user_roles_ID` FOREIGN KEY (`user_role_id`) REFERENCES `roles` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`ID`,`username`,`first_name`,`last_name`,`password`,`user_role_id`,`updated_on`,`created_on`) values (97,'abiral','Abiral','Neupane','21232f297a57a5a743894a0e4a801fc3',23,'0000-00-00 00:00:00','2015-12-24 19:27:59'),(107,'admin','Administrators','Shresthass','admin',21,'0000-00-00 00:00:00',NULL),(109,'rubal','Rubalsss','Shrestha','rubal',20,'0000-00-00 00:00:00','2015-12-27 10:37:06'),(110,'madhukar','Madhukar','Subedi','madhukar',22,'2015-12-27 10:38:20','2015-12-27 10:38:20'),(111,'min','Mishal','Rai','min',22,'2015-12-28 15:32:23','2015-12-27 10:38:42'),(112,'prajwal','Prajwal','Acharya','prajwal',20,'2015-12-27 18:02:49','2015-12-27 10:39:13'),(115,'kancha','Kancha','Kaji','kancha',20,'2016-01-12 17:08:24','2016-01-12 17:08:24'),(116,'kishor','Kishor','Mahato','kishor',20,'2016-01-14 16:21:29','2016-01-14 16:21:29'),(117,'ashok','Ashok','Maharjan','ashok',20,'2016-01-14 16:22:39','2016-01-14 16:22:39'),(120,'alisha','Alisha','Khadgi','alisha',22,'2016-01-20 09:40:09','2016-01-14 16:24:43'),(121,'sudeep','Sudeep','Subedi','sudeep',20,'2016-01-14 16:25:11','2016-01-14 16:25:11'),(122,'uzess','Yujesh','Shrestha','uzess',20,'0000-00-00 00:00:00','2016-01-20 09:39:33');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
