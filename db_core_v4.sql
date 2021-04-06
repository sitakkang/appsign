/*
SQLyog Ultimate v12.4.3 (64 bit)
MySQL - 10.1.30-MariaDB : Database - db_core_v4
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
-- CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_core_v4` /*!40100 DEFAULT CHARACTER SET latin1 */;

-- USE `db_core_v4`;

/*Table structure for table `conf_level` */

-- DROP TABLE IF EXISTS `conf_level`;

CREATE TABLE `conf_level` (
  `id_level` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `conf_level` */

insert  into `conf_level`(`id_level`,`name`) values 
(1,'Superadmin'),
(2,'Admin');

/*Table structure for table `conf_menu` */

DROP TABLE IF EXISTS `conf_menu`;

CREATE TABLE `conf_menu` (
  `id_menu` int(10) NOT NULL AUTO_INCREMENT,
  `icon` varchar(30) NOT NULL,
  `icon2` varchar(150) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `link` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `akses` tinyint(1) NOT NULL,
  `sub` tinyint(1) NOT NULL,
  `level` text NOT NULL,
  `position` int(2) NOT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `conf_menu` */

insert  into `conf_menu`(`id_menu`,`icon`,`icon2`,`name`,`link`,`status`,`akses`,`sub`,`level`,`position`) values 
(1,'fa-desktop','','Dashboard','home',1,1,1,'\"1\",\"2\"',1),
(2,'fa-cogs','','Configuration','admin/gen_modul',1,1,1,'\"1\",\"2\"',2);

/*Table structure for table `conf_submenu` */

DROP TABLE IF EXISTS `conf_submenu`;

CREATE TABLE `conf_submenu` (
  `id_submenu` int(5) NOT NULL AUTO_INCREMENT,
  `id_menu` int(5) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `icon2` varchar(150) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `link` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `level` text NOT NULL,
  `position` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_submenu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `conf_submenu` */

/*Table structure for table `conf_users` */

DROP TABLE IF EXISTS `conf_users`;

CREATE TABLE `conf_users` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(60) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `salt` varchar(15) NOT NULL,
  `level` tinyint(2) NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `conf_users` */

insert  into `conf_users`(`id_user`,`fullname`,`avatar`,`username`,`password`,`salt`,`level`,`last_login`,`ip_address`,`status`) values 
(1,'Superadmin','img/avatar/6U6lk2At.jpg','admin','89a0c6ee2ad740022ce185004dd64cca98c04b51','Wb8e.?s5',1,'2019-02-25 12:59:11','127.0.0.1',1),
(2,'Ardi','','ardi','00cc677ebf28c2788351082fe42ccc8982437a9c','+qt_a0Wy',1,'0000-00-00 00:00:00','',1);

/*Table structure for table `temp_login` */

DROP TABLE IF EXISTS `temp_login`;

CREATE TABLE `temp_login` (
  `id_temp` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(5) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  `nama_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_temp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `temp_login` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
