/*
SQLyog Professional v12.09 (64 bit)
MySQL - 10.1.16-MariaDB : Database - kurir
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`kurir` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `kurir`;

/*Table structure for table `paket` */

DROP TABLE IF EXISTS `paket`;

CREATE TABLE `paket` (
  `id_paket` int(11) NOT NULL AUTO_INCREMENT,
  `awb` varchar(13) NOT NULL,
  `id_user` int(11) NOT NULL,
  `pengirim` varchar(50) NOT NULL,
  `telp_pengirim` varchar(25) NOT NULL,
  `penerima_tertera` varchar(30) NOT NULL,
  `alamat_penerima` text NOT NULL,
  `telp_penerima` varchar(25) NOT NULL,
  `jenis_barang` enum('Dokumen','Paket') NOT NULL,
  `qty` int(3) NOT NULL,
  `width` int(3) NOT NULL,
  `length` int(3) NOT NULL,
  `height` int(3) NOT NULL,
  `kendaraan` enum('Motor','Mobil') NOT NULL,
  `deskripsi_barang` varchar(50) DEFAULT NULL,
  `status_pengiriman` enum('Manifested','On-Process','Delivered') DEFAULT NULL,
  `tgl_input` date NOT NULL,
  `tgl_approve` date NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_paket`,`tgl_approve`),
  UNIQUE KEY `kode paket` (`awb`),
  KEY `id_user` (`id_paket`),
  KEY `id_kurir` (`id_user`),
  CONSTRAINT `paket_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `paket` */

insert  into `paket`(`id_paket`,`awb`,`id_user`,`pengirim`,`telp_pengirim`,`penerima_tertera`,`alamat_penerima`,`telp_penerima`,`jenis_barang`,`qty`,`width`,`length`,`height`,`kendaraan`,`deskripsi_barang`,`status_pengiriman`,`tgl_input`,`tgl_approve`,`photo`) values (10,'11111111111',35,'a','(1111) 1111-1111','b','c','(2222) 2222-2222','Dokumen',1,2,3,4,'Mobil','d','Delivered','2017-12-14','2017-12-23','1513270856787.PNG'),(11,'22222222222',35,'asd','(2313) 1313-1312','asdasd','adad1231','(1312) 3131-2312','Dokumen',1,1,3,4,'Mobil','dada','Delivered','2017-12-14','2017-07-06','1513272952663.png');

/*Table structure for table `pengiriman` */

DROP TABLE IF EXISTS `pengiriman`;

CREATE TABLE `pengiriman` (
  `id_pengiriman` int(11) NOT NULL AUTO_INCREMENT,
  `id_paket` int(11) NOT NULL,
  `drs` varchar(15) NOT NULL,
  `penerima_paket` varchar(50) DEFAULT NULL,
  `hubungan_penerima` varchar(50) DEFAULT NULL,
  `status_pod` varchar(50) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `tgl_tiba` varchar(30) NOT NULL,
  `keterangan_status` text NOT NULL,
  `foto_barang` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pengiriman`),
  UNIQUE KEY `id_paket` (`id_paket`),
  UNIQUE KEY `drs` (`drs`),
  KEY `kode_pengiriman` (`id_paket`),
  CONSTRAINT `pengiriman_ibfk_1` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pengiriman` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `type` enum('admin','kurir') NOT NULL,
  `foto_profil` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `telp` varchar(25) NOT NULL,
  `status` enum('aktif','non-aktif') NOT NULL,
  `tanggal_input` date DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`id_user`,`email`,`pass`,`nama`,`type`,`foto_profil`,`alamat`,`telp`,`status`,`tanggal_input`) values (35,'admin@gmail.com','21232f297a57a5a743894a0e4a801fc3','Dwi Yudi Rayi Anugrah','admin','default.png','Subang','085320280635','aktif','2017-12-13'),(36,'dedepermana@gmail.com','21232f297a57a5a743894a0e4a801fc3','Dede Permana','kurir','1512976597434.png','Subang','(0853) 1521-5835','aktif','2017-12-14'),(37,'kurir123@gmail.com','d41d8cd98f00b204e9800998ecf8427e','kurir','admin','1513175659545.jpg','a','(1111) 1111-1111','aktif','2017-12-15');

/*Table structure for table `history_pengiriman` */

DROP TABLE IF EXISTS `history_pengiriman`;

/*!50001 DROP VIEW IF EXISTS `history_pengiriman` */;
/*!50001 DROP TABLE IF EXISTS `history_pengiriman` */;

/*!50001 CREATE TABLE  `history_pengiriman`(
 `id_paket` int(11) ,
 `id_pengiriman` int(11) ,
 `id_user` int(11) ,
 `awb` varchar(13) ,
 `drs` varchar(15) ,
 `pengirim` varchar(50) ,
 `telp_pengirim` varchar(25) ,
 `penerima_tertera` varchar(30) ,
 `alamat_penerima` text ,
 `telp_penerima` varchar(25) ,
 `jenis_barang` enum('Dokumen','Paket') ,
 `deskripsi_barang` varchar(50) ,
 `status_pengiriman` enum('Manifested','On-Process','Delivered') ,
 `status_pod` varchar(50) ,
 `penerima_paket` varchar(50) ,
 `hubungan_penerima` varchar(50) ,
 `keterangan_status` text ,
 `signature` varchar(100) ,
 `foto_barang` varchar(100) ,
 `tgl_input` date ,
 `tgl_approve` date ,
 `tgl_tiba` varchar(30) 
)*/;

/*Table structure for table `view_pengiriman` */

DROP TABLE IF EXISTS `view_pengiriman`;

/*!50001 DROP VIEW IF EXISTS `view_pengiriman` */;
/*!50001 DROP TABLE IF EXISTS `view_pengiriman` */;

/*!50001 CREATE TABLE  `view_pengiriman`(
 `id_pengiriman` int(11) ,
 `id_paket` int(11) ,
 `id_user` int(11) ,
 `awb` varchar(13) ,
 `drs` varchar(15) ,
 `pengirim` varchar(50) ,
 `telp_pengirim` varchar(25) ,
 `penerima_tertera` varchar(30) ,
 `alamat_penerima` text ,
 `telp_penerima` varchar(25) ,
 `jenis_barang` enum('Dokumen','Paket') ,
 `status_pengiriman` enum('Manifested','On-Process','Delivered') ,
 `tgl_input` date ,
 `tgl_approve` date 
)*/;

/*View structure for view history_pengiriman */

/*!50001 DROP TABLE IF EXISTS `history_pengiriman` */;
/*!50001 DROP VIEW IF EXISTS `history_pengiriman` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`id2950533_kurirapp`@`%` SQL SECURITY DEFINER VIEW `history_pengiriman` AS select `kurir`.`paket`.`id_paket` AS `id_paket`,`kurir`.`pengiriman`.`id_pengiriman` AS `id_pengiriman`,`kurir`.`paket`.`id_user` AS `id_user`,`kurir`.`paket`.`awb` AS `awb`,`kurir`.`pengiriman`.`drs` AS `drs`,`kurir`.`paket`.`pengirim` AS `pengirim`,`kurir`.`paket`.`telp_pengirim` AS `telp_pengirim`,`kurir`.`paket`.`penerima_tertera` AS `penerima_tertera`,`kurir`.`paket`.`alamat_penerima` AS `alamat_penerima`,`kurir`.`paket`.`telp_penerima` AS `telp_penerima`,`kurir`.`paket`.`jenis_barang` AS `jenis_barang`,`kurir`.`paket`.`deskripsi_barang` AS `deskripsi_barang`,`kurir`.`paket`.`status_pengiriman` AS `status_pengiriman`,`kurir`.`pengiriman`.`status_pod` AS `status_pod`,`kurir`.`pengiriman`.`penerima_paket` AS `penerima_paket`,`kurir`.`pengiriman`.`hubungan_penerima` AS `hubungan_penerima`,`kurir`.`pengiriman`.`keterangan_status` AS `keterangan_status`,`kurir`.`pengiriman`.`signature` AS `signature`,`kurir`.`pengiriman`.`foto_barang` AS `foto_barang`,`kurir`.`paket`.`tgl_input` AS `tgl_input`,`kurir`.`paket`.`tgl_approve` AS `tgl_approve`,`kurir`.`pengiriman`.`tgl_tiba` AS `tgl_tiba` from (`paket` join `pengiriman` on((`kurir`.`pengiriman`.`id_paket` = `kurir`.`paket`.`id_paket`))) where (`kurir`.`paket`.`status_pengiriman` = 'Done') */;

/*View structure for view view_pengiriman */

/*!50001 DROP TABLE IF EXISTS `view_pengiriman` */;
/*!50001 DROP VIEW IF EXISTS `view_pengiriman` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`id2950533_kurirapp`@`%` SQL SECURITY DEFINER VIEW `view_pengiriman` AS select `kurir`.`pengiriman`.`id_pengiriman` AS `id_pengiriman`,`kurir`.`paket`.`id_paket` AS `id_paket`,`kurir`.`paket`.`id_user` AS `id_user`,`kurir`.`paket`.`awb` AS `awb`,`kurir`.`pengiriman`.`drs` AS `drs`,`kurir`.`paket`.`pengirim` AS `pengirim`,`kurir`.`paket`.`telp_pengirim` AS `telp_pengirim`,`kurir`.`paket`.`penerima_tertera` AS `penerima_tertera`,`kurir`.`paket`.`alamat_penerima` AS `alamat_penerima`,`kurir`.`paket`.`telp_penerima` AS `telp_penerima`,`kurir`.`paket`.`jenis_barang` AS `jenis_barang`,`kurir`.`paket`.`status_pengiriman` AS `status_pengiriman`,`kurir`.`paket`.`tgl_input` AS `tgl_input`,`kurir`.`paket`.`tgl_approve` AS `tgl_approve` from (`paket` join `pengiriman` on((`kurir`.`pengiriman`.`id_paket` = `kurir`.`paket`.`id_paket`))) where (`kurir`.`paket`.`status_pengiriman` = 'Approved') */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
