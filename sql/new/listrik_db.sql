-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_listrik.level
CREATE TABLE IF NOT EXISTS `level` (
  `id_level` int NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(50) NOT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.level: ~2 rows (approximately)
INSERT INTO `level` (`id_level`, `nama_level`) VALUES
	(1, 'admin'),
	(2, 'pelanggan');

-- Dumping structure for table db_listrik.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nomor_kwh` varchar(20) DEFAULT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `alamat` text,
  `id_tarif` int DEFAULT NULL,
  `id_level` int DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`),
  UNIQUE KEY `nomor_kwh` (`nomor_kwh`),
  KEY `id_tarif` (`id_tarif`),
  CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`id_tarif`) REFERENCES `tarif` (`id_tarif`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.pelanggan: ~4 rows (approximately)
INSERT INTO `pelanggan` (`id_pelanggan`, `username`, `password`, `nomor_kwh`, `nama_pelanggan`, `alamat`, `id_tarif`, `id_level`) VALUES
	(4, 'muni', 'muniganteng', '123423', 'muqniansyah', 'jalan bahagia', 7, 2),
	(5, 'anton', 'anton123', '4562342', 'antonia simatupang', 'pomdok kelapa', 7, 2),
	(6, 'bima', 'bimaganteng', '234322', 'bima aja', 'jalan iskandar', 7, 2),
	(7, 'ayu', 'ayucantik', '345634', 'rohmah hayyu', 'jalan babelan', 8, 2);

-- Dumping structure for procedure db_listrik.pelanggan_daya_900
DELIMITER //
CREATE PROCEDURE `pelanggan_daya_900`()
BEGIN
  SELECT 
    pel.id_pelanggan,
    pel.nama_pelanggan,
    pel.nomor_kwh,
    t.daya
  FROM pelanggan pel
  JOIN tarif t ON pel.id_tarif = t.id_tarif
  WHERE t.daya = '900';
END//
DELIMITER ;

-- Dumping structure for table db_listrik.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `id_tagihan` int DEFAULT NULL,
  `id_tarif` int DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `bulan_bayar` varchar(20) DEFAULT NULL,
  `tahun_bayar` year DEFAULT NULL,
  `biaya_admin` decimal(10,2) DEFAULT NULL,
  `total_bayar` decimal(15,2) DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `status_bayar` enum('menunggu verifikasi','sudah diverifikasi') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'menunggu verifikasi',
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_tagihan` (`id_tagihan`),
  KEY `id_tarif` (`id_tarif`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_tagihan`) REFERENCES `tagihan` (`id_tagihan`),
  CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_tarif`) REFERENCES `tarif` (`id_tarif`),
  CONSTRAINT `pembayaran_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.pembayaran: ~5 rows (approximately)
INSERT INTO `pembayaran` (`id_pembayaran`, `id_tagihan`, `id_tarif`, `tanggal_pembayaran`, `bulan_bayar`, `tahun_bayar`, `biaya_admin`, `total_bayar`, `id_user`, `bukti_bayar`, `status_bayar`) VALUES
	(10, 10, NULL, '2025-07-21', '0', NULL, NULL, 670340.00, NULL, 'bukti_10_1753107412.png', 'menunggu verifikasi'),
	(11, 9, NULL, '2025-07-21', '0', NULL, NULL, 6050.00, NULL, 'bukti_9_1753107572.png', 'sudah diverifikasi'),
	(12, 11, NULL, '2025-07-21', '0', NULL, NULL, -6703400.00, NULL, 'bukti_11_1753107769.png', 'menunggu verifikasi'),
	(13, 12, NULL, '2025-07-21', '0', NULL, NULL, 1471360.00, NULL, 'bukti_12_1753107947.png', 'menunggu verifikasi'),
	(14, 13, NULL, '2025-07-24', '0', NULL, NULL, 739599190.00, NULL, 'bukti_13_1753323976.png', 'menunggu verifikasi');

-- Dumping structure for table db_listrik.penggunaan
CREATE TABLE IF NOT EXISTS `penggunaan` (
  `id_penggunaan` int NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` year DEFAULT NULL,
  `meter_awal` int DEFAULT NULL,
  `meter_akhir` int DEFAULT NULL,
  PRIMARY KEY (`id_penggunaan`),
  KEY `id_pelanggan` (`id_pelanggan`),
  KEY `idx_penggunaan_pelanggan` (`id_pelanggan`),
  CONSTRAINT `penggunaan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.penggunaan: ~7 rows (approximately)
INSERT INTO `penggunaan` (`id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `meter_awal`, `meter_akhir`) VALUES
	(9, 4, 'januari', '2012', 12312, 12322),
	(10, 4, 'april', '2010', 1234, 2342),
	(11, 4, 'maret', '2019', 12312, 1232),
	(12, 4, 'februari', '2012', 3243, 5675),
	(13, 5, 'februari', '2025', 12034, 1234512),
	(17, 4, 'Juli', '2025', 100, 150),
	(19, 4, 'Juli', '2025', 100, 150);

-- Dumping structure for table db_listrik.tagihan
CREATE TABLE IF NOT EXISTS `tagihan` (
  `id_tagihan` int NOT NULL AUTO_INCREMENT,
  `id_penggunaan` int DEFAULT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` year DEFAULT NULL,
  `jumlah_meter` int DEFAULT NULL,
  `status` enum('belum dibayar','sudah dibayar','menunggu verifikasi') DEFAULT NULL,
  PRIMARY KEY (`id_tagihan`),
  KEY `id_penggunaan` (`id_penggunaan`),
  KEY `id_pelanggan` (`id_pelanggan`),
  CONSTRAINT `tagihan_ibfk_1` FOREIGN KEY (`id_penggunaan`) REFERENCES `penggunaan` (`id_penggunaan`),
  CONSTRAINT `tagihan_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.tagihan: ~7 rows (approximately)
INSERT INTO `tagihan` (`id_tagihan`, `id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `jumlah_meter`, `status`) VALUES
	(9, 9, 4, 'januari', '2012', 10, 'sudah dibayar'),
	(10, 10, 4, 'april', '2010', 1108, 'menunggu verifikasi'),
	(11, 11, 4, 'maret', '2019', -11080, 'menunggu verifikasi'),
	(12, 12, 4, 'februari', '2012', 2432, 'menunggu verifikasi'),
	(13, 13, 5, 'februari', '2025', 1222478, 'menunggu verifikasi'),
	(15, 17, 4, 'Juli', '2025', 50, 'belum dibayar'),
	(17, 19, 4, 'Juli', '2025', 50, 'belum dibayar');

-- Dumping structure for table db_listrik.tarif
CREATE TABLE IF NOT EXISTS `tarif` (
  `id_tarif` int NOT NULL AUTO_INCREMENT,
  `daya` varchar(20) DEFAULT NULL,
  `tarifperkwh` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_tarif`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.tarif: ~5 rows (approximately)
INSERT INTO `tarif` (`id_tarif`, `daya`, `tarifperkwh`) VALUES
	(6, '450', 415.00),
	(7, '900', 900.00),
	(8, '1300', 1478.00),
	(9, '2200', 1467.00),
	(11, '4500', 1675.00);

-- Dumping structure for function db_listrik.total_penggunaan_bulanan
DELIMITER //
CREATE FUNCTION `total_penggunaan_bulanan`(
  pid_pelanggan INT,
  pbulan VARCHAR(20),
  ptahun INT
) RETURNS int
    DETERMINISTIC
BEGIN
  DECLARE total INT;

  SELECT SUM(meter_akhir - meter_awal) INTO total
  FROM penggunaan
  WHERE id_pelanggan = pid_pelanggan
    AND bulan = pbulan
    AND tahun = ptahun;

  RETURN IFNULL(total, 0);
END//
DELIMITER ;

-- Dumping structure for table db_listrik.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_admin` varchar(100) DEFAULT NULL,
  `id_level` int DEFAULT '1',
  PRIMARY KEY (`id_user`),
  KEY `id_level` (`id_level`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `level` (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.user: ~1 rows (approximately)
INSERT INTO `user` (`id_user`, `username`, `password`, `nama_admin`, `id_level`) VALUES
	(1, 'admin', 'admin123', NULL, 1);

-- Dumping structure for view db_listrik.v_penggunaan_listrik
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_penggunaan_listrik` (
	`id_penggunaan` INT(10) NOT NULL,
	`nama_pelanggan` VARCHAR(100) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`nomor_kwh` VARCHAR(20) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`bulan` VARCHAR(20) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`tahun` YEAR NULL,
	`meter_awal` INT(10) NULL,
	`meter_akhir` INT(10) NULL,
	`jumlah_meter` BIGINT(19) NULL
) ENGINE=MyISAM;

-- Dumping structure for view db_listrik.v_penggunaan_listrik
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_penggunaan_listrik`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_penggunaan_listrik` AS select `p`.`id_penggunaan` AS `id_penggunaan`,`pel`.`nama_pelanggan` AS `nama_pelanggan`,`pel`.`nomor_kwh` AS `nomor_kwh`,`p`.`bulan` AS `bulan`,`p`.`tahun` AS `tahun`,`p`.`meter_awal` AS `meter_awal`,`p`.`meter_akhir` AS `meter_akhir`,(`p`.`meter_akhir` - `p`.`meter_awal`) AS `jumlah_meter` from (`penggunaan` `p` join `pelanggan` `pel` on((`p`.`id_pelanggan` = `pel`.`id_pelanggan`)));

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
