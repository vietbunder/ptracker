/*
SQLyog Community v11.24 (32 bit)
MySQL - 5.5.27 : Database - db_ptracker_rev
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_ptracker_rev` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_ptracker_rev`;

/*Table structure for table `authassignment` */

DROP TABLE IF EXISTS `authassignment`;

CREATE TABLE `authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `authassignment` */

insert  into `authassignment`(`itemname`,`userid`,`bizrule`,`data`) values ('Admin','4',NULL,NULL),('Manager','2',NULL,NULL),('Manager','7',NULL,NULL),('Staff','3',NULL,NULL),('Staff','5',NULL,NULL);

/*Table structure for table `authitem` */

DROP TABLE IF EXISTS `authitem`;

CREATE TABLE `authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `authitem` */

insert  into `authitem`(`name`,`type`,`description`,`bizrule`,`data`) values ('Admin',2,'Superuser',NULL,'N;'),('Manager',2,'Project manager',NULL,'N;'),('Staff',2,'Commmon user',NULL,'N;');

/*Table structure for table `authitemchild` */

DROP TABLE IF EXISTS `authitemchild`;

CREATE TABLE `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `authitemchild` */

/*Table structure for table `t_account` */

DROP TABLE IF EXISTS `t_account`;

CREATE TABLE `t_account` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `t_account` */

insert  into `t_account`(`id`,`username`,`password`,`timestamp`) values (2,'Yoel','bbfb3b97637d3caa18d4f73c6bf1b3b6','2015-07-07 13:02:23'),(3,'ramperto','bbfb3b97637d3caa18d4f73c6bf1b3b6','2015-07-08 14:09:33'),(4,'admin','bbfb3b97637d3caa18d4f73c6bf1b3b6','2015-07-10 15:41:29'),(5,'jojo','bbfb3b97637d3caa18d4f73c6bf1b3b6','2015-07-10 15:42:35'),(7,'riris','bbfb3b97637d3caa18d4f73c6bf1b3b6','2015-08-06 14:32:45');

/*Table structure for table `t_member` */

DROP TABLE IF EXISTS `t_member`;

CREATE TABLE `t_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `account_id` int(10) NOT NULL,
  `name` varchar(64) NOT NULL,
  `department` varchar(64) NOT NULL,
  `role` varchar(64) NOT NULL,
  `last_login` date DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `f_member_account` (`account_id`),
  CONSTRAINT `f_member_account` FOREIGN KEY (`account_id`) REFERENCES `t_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `t_member` */

insert  into `t_member`(`id`,`account_id`,`name`,`department`,`role`,`last_login`,`timestamp`) values (1,2,'Yoel Rolas Simanjuntak','MIS','Manager','2015-08-14','2015-07-07 13:02:23'),(3,3,'Ramperto Pasaribu','MIS','Staff','2015-08-14','2015-07-08 14:09:33'),(4,4,'Administrator','MIS','Admin','2015-08-14','2015-07-10 15:41:52'),(5,5,'Jojo Hutagalung','MIS','Staff','2015-08-14','2015-07-10 15:42:35'),(7,7,'Riris Manik','MIS','Manager','2015-08-13','2015-08-06 14:32:45');

/*Table structure for table `t_project` */

DROP TABLE IF EXISTS `t_project`;

CREATE TABLE `t_project` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `creator_id` int(10) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` varchar(64) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `f_project_account` (`creator_id`),
  CONSTRAINT `f_project_account` FOREIGN KEY (`creator_id`) REFERENCES `t_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `t_project` */

insert  into `t_project`(`id`,`code`,`creator_id`,`name`,`description`,`start_date`,`due_date`,`status`,`deleted`,`timestamp`) values (11,'CR',2,'Chernobyl Reborn','Just another project :)','2015-08-14','2015-08-21','Active',0,'2015-08-13 16:59:16'),(12,'KP',7,'Kerja Praktek','New project','2015-08-17','2015-08-21','Upcoming',0,'2015-08-13 17:01:14'),(13,'CCTV',2,'Aplikasi Manajemen Aset CCTV','Test :)','2015-08-14','2015-08-17','Active',0,'2015-08-14 08:49:59');

/*Table structure for table `t_task` */

DROP TABLE IF EXISTS `t_task`;

CREATE TABLE `t_task` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `project_id` int(10) NOT NULL,
  `creator_id` int(10) NOT NULL,
  `has_parent` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(10) DEFAULT NULL,
  `title` varchar(64) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` varchar(64) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `f_task_project` (`project_id`),
  KEY `f_task_account` (`creator_id`),
  KEY `f_task_task` (`parent_id`),
  CONSTRAINT `f_task_account` FOREIGN KEY (`creator_id`) REFERENCES `t_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `f_task_project` FOREIGN KEY (`project_id`) REFERENCES `t_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `f_task_task` FOREIGN KEY (`parent_id`) REFERENCES `t_task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;

/*Data for the table `t_task` */

insert  into `t_task`(`id`,`code`,`project_id`,`creator_id`,`has_parent`,`parent_id`,`title`,`start_date`,`due_date`,`status`,`deleted`,`timestamp`) values (109,'CR.001',11,2,0,NULL,'Requirement gathering','2015-08-14','2015-08-17','Not complete',0,'2015-08-14 10:21:56'),(110,'CR.002',11,2,0,NULL,'Implementation','2015-08-14','2015-08-21','Not complete',0,'2015-08-14 10:50:06'),(111,'CR.002.001',11,2,1,110,'Modul A','2015-08-14','2015-08-20','Not complete',0,'2015-08-14 10:50:11'),(112,'CR.002.002',11,2,1,110,'Modul B','2015-08-20','2015-08-21','Not complete',0,'2015-08-14 10:23:33');

/*Table structure for table `t_task_assignment` */

DROP TABLE IF EXISTS `t_task_assignment`;

CREATE TABLE `t_task_assignment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) NOT NULL,
  `member_id` int(10) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `f_task_assignment_task` (`task_id`),
  KEY `f_task_assignment_account` (`member_id`),
  CONSTRAINT `f_task_assignment_account` FOREIGN KEY (`member_id`) REFERENCES `t_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `f_task_assignment_task` FOREIGN KEY (`task_id`) REFERENCES `t_task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;

/*Data for the table `t_task_assignment` */

insert  into `t_task_assignment`(`id`,`task_id`,`member_id`,`deleted`,`timestamp`) values (79,109,5,0,'2015-08-14 10:21:56'),(80,111,3,0,'2015-08-14 10:23:05'),(81,112,5,0,'2015-08-14 10:23:33');

/*Table structure for table `t_task_log` */

DROP TABLE IF EXISTS `t_task_log`;

CREATE TABLE `t_task_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  `member_id` int(10) NOT NULL,
  `task_assignment_id` int(10) DEFAULT NULL,
  `task_title` varchar(64) DEFAULT NULL,
  `description` text,
  `date` date NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `f_task_log_task_assignment` (`task_assignment_id`),
  KEY `f_task_log_account` (`member_id`),
  CONSTRAINT `f_task_log_account` FOREIGN KEY (`member_id`) REFERENCES `t_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `f_task_log_task_assignment` FOREIGN KEY (`task_assignment_id`) REFERENCES `t_task_assignment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Data for the table `t_task_log` */

insert  into `t_task_log`(`id`,`type`,`member_id`,`task_assignment_id`,`task_title`,`description`,`date`,`deleted`,`timestamp`) values (26,'Project',5,79,NULL,'','2015-08-14',0,'2015-08-14 10:28:24'),(27,'Non-project',5,NULL,'Test 1','','2015-08-14',0,'2015-08-14 10:29:34'),(28,'Non-project',5,NULL,'Test 2','','2015-08-15',0,'2015-08-14 10:30:33'),(29,'Project',5,79,NULL,NULL,'2015-08-16',0,'2015-08-14 10:31:13'),(30,'Project',5,79,NULL,NULL,'2015-08-17',0,'2015-08-14 10:31:26'),(31,'Project',5,80,NULL,'','2015-08-18',0,'2015-08-14 10:35:05'),(32,'Project',3,80,NULL,'','2015-08-19',0,'2015-08-14 10:35:09'),(33,'Non-project',3,NULL,'Perto','','2015-08-18',0,'2015-08-14 10:34:36'),(34,'Project',3,80,NULL,'','2015-08-21',0,'2015-08-14 10:38:46'),(35,'Project',3,80,NULL,'','2015-08-20',0,'2015-08-14 10:39:12'),(36,'Project',5,80,NULL,'','2015-08-14',0,'2015-08-14 10:47:23');

/*Table structure for table `t_task_report` */

DROP TABLE IF EXISTS `t_task_report`;

CREATE TABLE `t_task_report` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `task_assignment_id` int(10) NOT NULL,
  `start_date` date NOT NULL,
  `finish_date` date NOT NULL,
  `description` text,
  `status` varchar(64) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `f_task_report_task_assignment` (`task_assignment_id`),
  CONSTRAINT `f_task_report_task_assignment` FOREIGN KEY (`task_assignment_id`) REFERENCES `t_task_assignment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_task_report` */

/*!50106 set global event_scheduler = 1*/;

/* Event structure for event `update_project_status_active` */

/*!50106 DROP EVENT IF EXISTS `update_project_status_active`*/;

DELIMITER $$

/*!50106 CREATE DEFINER=`root`@`localhost` EVENT `update_project_status_active` ON SCHEDULE EVERY 1 MINUTE STARTS '2015-07-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE `t_project` SET t_project.status = "Active" WHERE t_project.start_date <= DATE(NOW()) AND t_project.status = "Upcoming" */$$
DELIMITER ;

/* Event structure for event `update_project_status_expired` */

/*!50106 DROP EVENT IF EXISTS `update_project_status_expired`*/;

DELIMITER $$

/*!50106 CREATE DEFINER=`root`@`localhost` EVENT `update_project_status_expired` ON SCHEDULE EVERY 1 MINUTE STARTS '2015-07-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE `t_project` SET t_project.status = "Expired" WHERE t_project.due_date < DATE(NOW()) AND t_project.status != "Complete" */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
