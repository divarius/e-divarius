/*
SQLyog Enterprise - MySQL GUI v8.18 
MySQL - 5.5.27 : Database - divarius
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`divarius` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `divarius`;

/*Table structure for table `categoria_habitaciones` */

DROP TABLE IF EXISTS `categoria_habitaciones`;

CREATE TABLE `categoria_habitaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `id_establecimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `categoria_habitaciones` */

insert  into `categoria_habitaciones`(`id`,`nombre`,`id_establecimiento`) values (1,'Single',1),(2,'Doble',1),(3,'Suite Junior2',1),(4,'Suite',1),(5,'Presidencial',2),(9,'Presidencial',1),(10,'asdfa',1),(11,'asdc',1);

/*Table structure for table `consumos_x_reservation` */

DROP TABLE IF EXISTS `consumos_x_reservation`;

CREATE TABLE `consumos_x_reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) DEFAULT NULL,
  `costo` decimal(7,2) DEFAULT NULL,
  `id_reservation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_consumos_x_reservation` (`id_reservation`),
  CONSTRAINT `FK_tbl_consumos_x_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Data for the table `consumos_x_reservation` */

insert  into `consumos_x_reservation`(`id`,`nombre`,`costo`,`id_reservation`) values (14,'crema','12542.00',110),(15,'dulce','12542.00',110),(16,'azucar','12542.00',110),(17,'crema de leche','50.00',109),(19,'melon','12.00',110),(20,'corteza de arbol','58.00',110),(21,'asdad','5.00',109),(28,'Saldo de la habitacion 05','1250.00',109);

/*Table structure for table `establecimientos` */

DROP TABLE IF EXISTS `establecimientos`;

CREATE TABLE `establecimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `ubicacion` varchar(250) DEFAULT NULL,
  `latitud` varchar(100) DEFAULT NULL,
  `longitud` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `establecimientos` */

insert  into `establecimientos`(`id`,`nombre`,`descripcion`,`ubicacion`,`latitud`,`longitud`) values (1,'TheAmblassador','Ninguna Descripcion','Cordoba','-1121','-12123'),(2,'TheAmblassador2','Ninguna Descripcion','Cordoba','123','456');

/*Table structure for table `establecimientos_x_user` */

DROP TABLE IF EXISTS `establecimientos_x_user`;

CREATE TABLE `establecimientos_x_user` (
  `id_establecimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_establecimiento`,`id_user`),
  UNIQUE KEY `id_establecimiento` (`id_establecimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `establecimientos_x_user` */

insert  into `establecimientos_x_user`(`id_establecimiento`,`id_user`) values (1,1);

/*Table structure for table `formas_de_pago` */

DROP TABLE IF EXISTS `formas_de_pago`;

CREATE TABLE `formas_de_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forma_de_pago` varchar(50) DEFAULT NULL,
  `id_establecimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `formas_de_pago` */

insert  into `formas_de_pago`(`id`,`forma_de_pago`,`id_establecimiento`) values (1,'Efectivo',1),(2,'Tarjeta',1),(3,'Efectivo',2);

/*Table structure for table `listas_de_precios` */

DROP TABLE IF EXISTS `listas_de_precios`;

CREATE TABLE `listas_de_precios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL,
  `fecha_desde` date DEFAULT NULL,
  `fecha_hasta` date DEFAULT NULL,
  `estado` varchar(100) DEFAULT 'ACTIVA',
  `id_establecimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `listas_de_precios` */

insert  into `listas_de_precios`(`id`,`nombre`,`fecha_desde`,`fecha_hasta`,`estado`,`id_establecimiento`) values (1,'Lista de precios original','2013-10-06','2013-10-07',NULL,1),(2,'Dia de la madre','2013-10-05','2013-10-20',NULL,1),(3,'Vacaciones','2014-01-01','2014-02-28','ACTIVA',1);

/*Table structure for table `loggedin_users` */

DROP TABLE IF EXISTS `loggedin_users`;

CREATE TABLE `loggedin_users` (
  `tbl_loggedin_users_id` int(11) NOT NULL AUTO_INCREMENT,
  `loggedusername` varchar(45) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `useragent` text NOT NULL,
  `lastactivity` int(20) DEFAULT NULL,
  `online` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`tbl_loggedin_users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `loggedin_users` */

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `tbl_messages_id` int(11) NOT NULL AUTO_INCREMENT,
  `senderid` int(11) NOT NULL,
  `receiverid` int(11) NOT NULL,
  `message` varchar(45) DEFAULT NULL,
  `subject` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `read` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`tbl_messages_id`),
  KEY `senderid` (`senderid`),
  KEY `receiverid` (`receiverid`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`senderid`) REFERENCES `users` (`tbl_users_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiverid`) REFERENCES `users` (`tbl_users_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `messages` */

/*Table structure for table `paquetes` */

DROP TABLE IF EXISTS `paquetes`;

CREATE TABLE `paquetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `paquetes` */

/*Table structure for table `pasajeros_x_reservation` */

DROP TABLE IF EXISTS `pasajeros_x_reservation`;

CREATE TABLE `pasajeros_x_reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) DEFAULT NULL,
  `dni` decimal(10,0) DEFAULT NULL,
  `id_reservation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_pasajeros_x_reservation` (`id_reservation`),
  CONSTRAINT `FK_tbl_pasajeros_x_reservation` FOREIGN KEY (`id_reservation`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

/*Data for the table `pasajeros_x_reservation` */

insert  into `pasajeros_x_reservation`(`id`,`nombre`,`dni`,`id_reservation`) values (41,'Alfajor','12354',110),(44,'roberto carlo','1111111',110),(46,'angel caido','9998778',110);

/*Table structure for table `picture_comments` */

DROP TABLE IF EXISTS `picture_comments`;

CREATE TABLE `picture_comments` (
  `tbl_picture_comments_id` int(11) NOT NULL AUTO_INCREMENT,
  `pictureid` int(11) NOT NULL,
  `commentbody` varchar(45) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `ownerid` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `web` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `like` int(11) DEFAULT NULL,
  `dislike` int(11) DEFAULT NULL,
  PRIMARY KEY (`tbl_picture_comments_id`),
  KEY `pictureid` (`pictureid`),
  KEY `ownerid` (`ownerid`),
  CONSTRAINT `picture_comments_ibfk_1` FOREIGN KEY (`pictureid`) REFERENCES `pictures` (`tbl_pictures_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `picture_comments_ibfk_2` FOREIGN KEY (`ownerid`) REFERENCES `users` (`tbl_users_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `picture_comments` */

/*Table structure for table `pictures` */

DROP TABLE IF EXISTS `pictures`;

CREATE TABLE `pictures` (
  `tbl_pictures_id` int(11) NOT NULL AUTO_INCREMENT,
  `picid` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `ownerid` int(11) NOT NULL,
  `privacy` int(11) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `dislikes` int(11) DEFAULT NULL,
  PRIMARY KEY (`tbl_pictures_id`),
  KEY `fk_tbl_pictures_tbl_picture_comments` (`picid`),
  KEY `ownerid` (`ownerid`),
  CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`ownerid`) REFERENCES `users` (`tbl_users_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pictures` */

/*Table structure for table `reservations` */

DROP TABLE IF EXISTS `reservations`;

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resort` varchar(250) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `email` varchar(250) DEFAULT 'No Disponible',
  `dni` varchar(200) DEFAULT 'No Disponible',
  `telefono` varchar(200) DEFAULT 'No Disponible',
  `nombre_apellido` varchar(200) DEFAULT NULL,
  `observaciones` varchar(250) DEFAULT 'No Disponible',
  `cant_adultos` decimal(2,0) DEFAULT NULL,
  `cant_menores` decimal(2,0) DEFAULT NULL,
  `status` enum('Habitacion no disponible','Reserva Web Pendiente de Pago','Reserva Pendiente de Pago medios tradicionales','Reserva Confirmada') DEFAULT 'Reserva Pendiente de Pago medios tradicionales',
  `forma_pago_reserva` varchar(25) DEFAULT NULL,
  `monto_reserva` decimal(7,0) DEFAULT NULL,
  `forma_pago_final` varchar(25) DEFAULT NULL,
  `monto_final` decimal(7,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=latin1;

/*Data for the table `reservations` */

insert  into `reservations`(`id`,`id_resort`,`start`,`end`,`email`,`dni`,`telefono`,`nombre_apellido`,`observaciones`,`cant_adultos`,`cant_menores`,`status`,`forma_pago_reserva`,`monto_reserva`,`forma_pago_final`,`monto_final`) values (109,'4','2013-09-10','2013-09-16',NULL,'11111111111111','12346578','Maxi','Una observacion','1','0','Reserva Confirmada','efectivo','0','efectivo','0'),(110,'1','2013-09-26','2013-10-02','','','1524221','maxi','una observacion','1','0','Reserva Confirmada','efectivo','0','efectivo','0'),(111,'5','2013-09-23','2013-09-25','maxi.solimo@gmail.com','3121575','4893791','Maxi','','1','0','Reserva Confirmada','efectivo','0','efectivo','0'),(112,'1','2013-10-03','2013-10-07','maxi.solimo@gmail.com','31219829','4893791','Maximiliano Solimo','','2','2','Reserva Confirmada','efectivo','0','efectivo','0'),(113,'2','2013-10-01','2013-10-07','maxi.solimo@gmail.com','31219829','4893791','Maximiliano Solimo','','2','2','Reserva Confirmada','efectivo','0','efectivo','0'),(114,'3','2013-10-09','2013-10-14','maxi.solimo@gmail.com','31219829','4893791','Maximiliano Solimo','','2','2','Reserva Pendiente de Pago medios tradicionales','efectivo','0','efectivo','0'),(115,'5','2013-10-02','2013-10-02','ana@gmail.com','123456','4893791','Ana','','1','0','Reserva Pendiente de Pago medios tradicionales','efectivo','0','efectivo','0'),(116,'6','2013-10-02','2013-10-02','anabel-san@hotmail.com.ar','123456789','4893791','Ana','','1','0','Reserva Pendiente de Pago medios tradicionales','efectivo','0','efectivo','0');

/*Table structure for table `resorts` */

DROP TABLE IF EXISTS `resorts`;

CREATE TABLE `resorts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `descripcion` text,
  `id_categoria` int(11) DEFAULT NULL,
  `id_establecimiento` int(11) DEFAULT NULL,
  `online_resort` int(1) DEFAULT '0',
  `cant_estandar_adultos` enum('1','2','3','4','5') DEFAULT NULL,
  `cant_estandar_menores` enum('1','2','3','4','5') DEFAULT NULL,
  `cant_maxima_adultos` enum('1','2','3','4','5') DEFAULT NULL,
  `cant_maxima_menores` enum('1','2','3','4','5') DEFAULT NULL,
  `observaciones` text,
  `resortactive` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

/*Data for the table `resorts` */

insert  into `resorts`(`id`,`name`,`descripcion`,`id_categoria`,`id_establecimiento`,`online_resort`,`cant_estandar_adultos`,`cant_estandar_menores`,`cant_maxima_adultos`,`cant_maxima_menores`,`observaciones`,`resortactive`) values (1,'Hab. 012','pruebaaa',1,1,1,NULL,NULL,NULL,NULL,NULL,1),(2,'Hab. 02','maxi',1,1,0,NULL,NULL,NULL,NULL,NULL,1),(3,'Hab. 03','Premiun',1,1,0,NULL,NULL,NULL,NULL,NULL,1),(4,'Hab. 04','una descriocn',2,1,0,NULL,NULL,NULL,NULL,NULL,1),(5,'Hab. 05','habitacion',2,1,0,NULL,NULL,NULL,NULL,NULL,2),(6,'Hab. 06','Habitacion 6',3,1,0,NULL,NULL,NULL,NULL,NULL,1),(7,'Hab. 07','Habitacion 7',3,1,0,NULL,NULL,NULL,NULL,NULL,2),(19,'Hab. 12','Una descripcion',1,1,0,'','','','','',1),(26,'Nueva Habitacion','Descripcion',9,NULL,1,NULL,NULL,NULL,NULL,NULL,0),(28,'Hab. 22','Descripcion',4,1,0,NULL,NULL,NULL,NULL,NULL,1),(29,'hab.45','Descripcion',3,1,0,NULL,NULL,NULL,NULL,NULL,1),(30,'Hab. 99','Una descripcion elegante',9,1,1,'2','2','3','3',NULL,NULL),(31,'Hab. 100','Descripcion',4,1,1,NULL,NULL,NULL,NULL,NULL,1),(32,'abc','Nueva',4,1,0,NULL,NULL,NULL,NULL,NULL,1),(33,'Hab. pruebaa',NULL,9,1,1,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `servicio_x_establecimiento` */

DROP TABLE IF EXISTS `servicio_x_establecimiento`;

CREATE TABLE `servicio_x_establecimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `descripcion` text,
  `id_establecimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `servicio_x_establecimiento` */

insert  into `servicio_x_establecimiento`(`id`,`nombre`,`descripcion`,`id_establecimiento`) values (1,'Frigo Bar',NULL,1),(2,'TV - Cable',NULL,1),(4,'aaa',NULL,2),(5,'Pesca',NULL,1),(6,'Pesca 2',NULL,NULL),(7,'asdfg',NULL,1),(8,'servicio nuevo2',NULL,1);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `tbl_users_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activationkey` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `secondname` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `dateofbirth` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interests` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profileprivacy` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accountactive` int(11) DEFAULT NULL,
  `accountblocked` int(11) DEFAULT NULL,
  `registereddate` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastloggenindate` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `appearonline` int(11) DEFAULT NULL,
  `userlvl` int(1) NOT NULL,
  `id_establecimiento` int(11) DEFAULT NULL,
  PRIMARY KEY (`tbl_users_id`),
  KEY `fk_tbl_users_tbl_pictures1` (`tbl_users_id`),
  KEY `fk_tbl_users_tbl_loggedin_users1` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`tbl_users_id`,`username`,`password`,`salt`,`activationkey`,`email`,`firstname`,`secondname`,`lastname`,`dateofbirth`,`address`,`country`,`interests`,`profileprivacy`,`accountactive`,`accountblocked`,`registereddate`,`lastloggenindate`,`appearonline`,`userlvl`,`id_establecimiento`) values (1,'administrador','7c4a8d09ca3762af61e59520943dc26494f8941b','3vWpuQ+xQ5QTKT+T0WouNKtdug0HAqN6Ll3yr4P1BiaoQ','ac140f4c202fee5a707cbe7b733d627c','maxi.solimo@gmail.com','Maximiliano','Jay','Solimo','02/12/1984','La Tablada 2820','AR','Musica','public',1,0,'1376589072','1381200129',1,0,1),(2,'recepcionista','7c4a8d09ca3762af61e59520943dc26494f8941b','qf36VlSVzGh4aLhsm6Dr/8BJsdruhGx2DXDCKB+4sov6u','058cb7fd12989f096f9908a824a1c46c','maxi2.solimo@gmail.com','Maxi','','Recepcionista','0','','AR','','public',1,0,'1379288477','1379360210',1,0,2);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
