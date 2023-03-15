/*Table structure for table `jadwal` */



DROP TABLE IF EXISTS `jadwal`;



CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL AUTO_INCREMENT,
  `id_kapal` int(11) DEFAULT NULL,
  `hari_jadwal` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') DEFAULT NULL,
  `jam_jadwal` time DEFAULT NULL,
  `stt_jadwal` enum('Y','N') DEFAULT NULL,
  PRIMARY KEY (`id_jadwal`),
  KEY `id_jadwal` (`id_jadwal`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;



/*Data for the table `jadwal` */



insert  into `jadwal`(`id_jadwal`,`id_kapal`,`hari_jadwal`,`jam_jadwal`,`stt_jadwal`) values (1,3,'senin','13:00:00','N'),(2,3,'jumat','13:00:00','N'),(3,3,'minggu','12:00:00','N'),(4,3,'selasa','12:00:00','N'),(5,3,'kamis','12:00:00','N'),(6,8,'selasa','07:00:00','N'),(7,8,'kamis','07:00:00','N'),(8,8,'sabtu','07:00:00','N'),(9,8,'minggu','07:00:00','N'),(10,15,'senin','07:30:00','N'),(11,3,'selasa','07:30:00','Y'),(12,15,'rabu','07:30:00','N'),(13,15,'kamis','07:30:00','N'),(14,15,'jumat','07:30:00','N'),(15,15,'sabtu','07:30:00','N'),(16,15,'minggu','07:30:00','Y'),(17,28,'senin','08:00:00','Y'),(18,5,'selasa','08:30:00','Y'),(19,17,'rabu','07:30:00','Y'),(20,7,'kamis','10:00:00','Y'),(21,21,'jumat','09:00:00','Y'),(22,18,'sabtu','08:00:00','Y'),(23,24,'minggu','08:00:00','Y'),(24,8,'senin','09:30:00','Y'),(25,25,'selasa','08:00:00','Y'),(26,4,'rabu','08:00:00','N'),(27,4,'rabu','09:00:00','Y'),(28,23,'kamis','08:00:00','N'),(29,19,'kamis','09:00:00','N'),(30,23,'jumat','09:30:00','Y'),(31,19,'sabtu','09:00:00','Y');



/*Table structure for table `kapal` */



DROP TABLE IF EXISTS `kapal`;



CREATE TABLE `kapal` (
  `id_kapal` int(11) NOT NULL AUTO_INCREMENT,
  `id_perusahaan` int(11) DEFAULT NULL,
  `nm_kapal` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_kapal`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;



/*Data for the table `kapal` */



insert  into `kapal`(`id_kapal`,`id_perusahaan`,`nm_kapal`) values (3,2,'asdsadsad'),(4,5,'DUMAI LINE -1'),(5,6,'BATAM JET 6'),(6,5,'DUMAI LINE -8'),(7,8,'BINTANG RIZKI EXP 89'),(8,5,'DUMAI EXPRESS 01'),(9,10,'RAHMAT JAYA 09'),(10,11,'ERA MANDIRI 1'),(11,8,'POLEWALI EXPRES 2'),(12,8,'MEGA FAJAR - 5'),(13,11,'MARINA BATAM 2'),(14,12,'MIKO NATALIA 99'),(15,6,'BATAM JET 2'),(16,7,'KARUNIA JAYA 02'),(17,11,'BATAM LINE -2'),(18,13,'BUDI JASA 37'),(19,5,'DUMAI LINE -2'),(20,12,'MIKO NATALIA 89'),(21,8,'BINTANG RIZKI EXP 99'),(22,8,'MEGA FAJAR -6'),(23,5,'DUMAI LINE -12'),(24,7,'CARORINA'),(25,5,'DUMAI EXPRESS 16'),(26,12,'MIKA NATALIA 33'),(27,5,'DUMAI LINE 12'),(28,6,'BATAM JET 3');



/*Table structure for table `kursi` */



DROP TABLE IF EXISTS `kursi`;



CREATE TABLE `kursi` (
  `id_kursi` int(11) NOT NULL AUTO_INCREMENT,
  `id_kapal` int(11) DEFAULT NULL,
  `no_kursi` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_kursi`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;



/*Data for the table `kursi` */



insert  into `kursi`(`id_kursi`,`id_kapal`,`no_kursi`) values (4,3,'xxxx'),(5,15,'A-1'),(6,15,'A-2'),(7,15,'B-1'),(8,15,'B-2'),(9,15,'C-1'),(10,15,'C-2'),(11,15,'D-1'),(12,15,'D-2');



/*Table structure for table `notifikasi` */



DROP TABLE IF EXISTS `notifikasi`;



CREATE TABLE `notifikasi` (
  `kdn` varchar(10) NOT NULL,
  `jenis` enum('danger','info','warning','success') DEFAULT NULL,
  `dc` enum('alert','callout') DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `head` text,
  `isi` text,
  PRIMARY KEY (`kdn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



/*Data for the table `notifikasi` */



insert  into `notifikasi`(`kdn`,`jenis`,`dc`,`icon`,`head`,`isi`) values ('NOT01','danger','alert','icon fas fa-exclamation-triangle','Log-In Gagal!','Username / Password Salah!'),('NOT02','danger','alert','icon fas fa-exclamation-triangle','Data Gagal di Input!','Ada kesalahan pada query, Silahkan cek lagi!!'),('NOT03','success','alert','icon fas fa-check','Data Berhasil di Input!','Data berhasil diinput kedalam Database!'),('NOT04','success','alert','icon fas fa-check','Data Berhasil di Ubah!','Data berhasil Diubah dari Database!'),('NOT05','warning','alert','icon fas fa-exclamation-triangle','Data Berhasil di Hapus!','Data berhasil Dibapus dari Database!'),('NOT06','warning','alert','icon fas fa-exclamation-triangle','Warning!','Masukan File!!'),('NOT07','danger','alert','icon fas fa-exclamation-triangle','Warning!','Esktensi File Tidak diperbolehkan!!'),('NOT08','danger','alert','icon fas fa-exclamation-triangle','Kesalahan!','Password yang Anda Masukan Salah!'),('NOT09','danger','alert','icon fas fa-exclamation-triangle','Kesalahan!','Password Baru Berbeda / Tidak Sama!'),('NOT11','warning','alert','icon fas fa-exclamation-triangle','Warning!','Username Telah Digunakan!');



/*Table structure for table `pelabuhan` */



DROP TABLE IF EXISTS `pelabuhan`;



CREATE TABLE `pelabuhan` (
  `id_pelabuhan` int(11) NOT NULL AUTO_INCREMENT,
  `nm_pelabuhan` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_pelabuhan`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;



/*Data for the table `pelabuhan` */



insert  into `pelabuhan`(`id_pelabuhan`,`nm_pelabuhan`) values (2,'zzzz'),(3,'cvcvc'),(4,'TANJUNG BALAI KARIMUN'),(5,'SUNGAI TOHOR'),(6,'REPAN'),(7,'SELAT PANJANG'),(8,'BUTON'),(9,'BENGKALIS'),(10,'DUMAI'),(11,'TANJUNG SAMAK'),(12,'URUNG'),(13,'PULAU BURUNG'),(14,'SUNGAI GUNTUNG'),(15,'PERIGI RAJA'),(16,'KUALA ENOK'),(17,'PULAU KIJANG'),(18,'KOTA BARU'),(19,'TANJUNG BATU'),(20,'PENYALAI'),(21,'KUALA TUNGKAL'),(22,'MORO'),(23,'DURAI'),(24,'SEMBUANG'),(25,'KUALA GAUNG'),(26,'TEMBILAHAN'),(27,'BATAM');



/*Table structure for table `perusahaan` */



DROP TABLE IF EXISTS `perusahaan`;



CREATE TABLE `perusahaan` (
  `id_perusahaan` int(11) NOT NULL AUTO_INCREMENT,
  `nm_perusahaan` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_perusahaan`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;



/*Data for the table `perusahaan` */



insert  into `perusahaan`(`id_perusahaan`,`nm_perusahaan`) values (2,'dasacsacas'),(3,'qweqweqwsqw'),(4,'zul'),(5,'PT. LESTARI INDOMA BAHARI '),(6,'PT. BATAM BAHARI SEJAHTERA'),(7,'PT. AYODHIA BAHARI'),(8,'PT. BATAM LESTARI BAHARI'),(9,'PT. BUDIMAN INDAH'),(10,'PT. PRIMA RAHMAT ABADI'),(11,'PT. MARINATAMA GEMANUSA'),(12,'PT. MIKO NATALIA'),(13,'PT. AWANG PASS');



/*Table structure for table `rute` */



DROP TABLE IF EXISTS `rute`;



CREATE TABLE `rute` (
  `id_rute` int(11) NOT NULL AUTO_INCREMENT,
  `id_jadwal` int(11) DEFAULT NULL,
  `harga_tiket` bigint(20) DEFAULT NULL,
  `pelabuhan_awal` int(11) DEFAULT NULL,
  `pelabuhan_akhir` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_rute`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;



/*Data for the table `rute` */



insert  into `rute`(`id_rute`,`id_jadwal`,`harga_tiket`,`pelabuhan_awal`,`pelabuhan_akhir`) values (7,1,11111111,3,2),(9,4,4444,3,2),(10,NULL,100000,27,9),(11,NULL,10000,27,9),(12,NULL,10000,27,27),(16,16,200000,27,7),(17,16,250000,27,9),(18,16,150000,27,11),(19,28,66,27,8);



/*Table structure for table `tiket` */



DROP TABLE IF EXISTS `tiket`;



CREATE TABLE `tiket` (
  `id_tiket` int(11) NOT NULL AUTO_INCREMENT,
  `id_rute` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_kursi` int(11) DEFAULT NULL,
  `nm_penumpang` varchar(150) DEFAULT NULL,
  `umur_penumpang` int(11) DEFAULT NULL,
  `jk_penumpang` enum('laki-laki','perempuan') DEFAULT NULL,
  `tgl_keberangkatan` date DEFAULT NULL,
  `bukti_pembayaran` text,
  `stt_tiket` enum('booking','payment','reschedule','cancel','waiting','success') DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `bp_admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tiket`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;



/*Data for the table `tiket` */



insert  into `tiket`(`id_tiket`,`id_rute`,`id_user`,`id_kursi`,`nm_penumpang`,`umur_penumpang`,`jk_penumpang`,`tgl_keberangkatan`,`bukti_pembayaran`,`stt_tiket`,`id_admin`,`bp_admin`) values (1,7,2,4,'xxx',33,'laki-laki','2021-06-25',NULL,'waiting',NULL,NULL),(2,7,2,4,'zulkifli',22,'perempuan','2021-06-25','2 - 789290_penguin_512x512-1.png','waiting',NULL,1),(3,7,2,4,'agum',12,'laki-laki','2021-06-25',NULL,'cancel',NULL,NULL),(4,7,2,4,'andi',21,'laki-laki','2021-06-25','4 - 789290_penguin_512x512.png','success',NULL,1),(5,7,2,4,'xxxxxxx',12,'laki-laki','2021-06-25','5 - 789290_penguin_512x512.png','payment',NULL,1),(6,16,2,5,'WERY',22,'laki-laki','2021-07-03','6 - 48459.jpg','payment',NULL,1),(7,18,2,5,'ANDI',21,'laki-laki','2021-07-04',NULL,'cancel',NULL,NULL),(8,16,5,5,'andi',21,'laki-laki','2021-07-11','8 - 1625973915837342381370249116181.jpg','success',NULL,1),(9,16,7,5,'Novi Hendri',20,'laki-laki','2021-07-04',NULL,'booking',NULL,NULL);



/*Table structure for table `user` */



DROP TABLE IF EXISTS `user`;



CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nm_user` varchar(100) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `foto_user` text,
  `akses` enum('admin','user','pimpinan') DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;



/*Data for the table `user` */



insert  into `user`(`id_user`,`nm_user`,`username`,`password`,`foto_user`,`akses`) values (1,'admin','admin','admin',NULL,'admin'),(2,'zulkilfi','user','user','user - 789290_penguin_512x512.png','user'),(3,'pimpinan','pimpinan','pimpinan',NULL,'pimpinan'),(4,'zul','12345','12345',NULL,'user'),(5,'andiii','andi','12345',NULL,'user'),(6,'zulkifli','zul123','12345',NULL,'user'),(7,'Novi Hendri','novi','novi',NULL,'user'),(8,'zulk','zul','zul',NULL,'user'),(9,'pani','pani','pani',NULL,'user'),(10,'kurnia','kurnia','123',NULL,'user');



/*Table structure for table `login` */



DROP TABLE IF EXISTS `login`;



/*!50001 DROP VIEW IF EXISTS `login` */;

/*!50001 DROP TABLE IF EXISTS `login` */;


/*!50001 CREATE TABLE  `login`(
 `id_user` int(11) ,
 `username` varchar(255) ,
 `password` varchar(32) ,
 `akses` enum('admin','user','pimpinan') ,
 `nm_user` varchar(100) ,
 `foto_user` text 
)*/;


/*View structure for view login */



/*!50001 DROP TABLE IF EXISTS `login` */;

/*!50001 DROP VIEW IF EXISTS `login` */;



/*!50001 CREATE VIEW `login` AS (select `user`.`id_user` AS `id_user`,`user`.`username` AS `username`,md5(`user`.`password`) AS `password`,`user`.`akses` AS `akses`,`user`.`nm_user` AS `nm_user`,`user`.`foto_user` AS `foto_user` from `user`) */;