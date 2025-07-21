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
  PRIMARY KEY (`id_pelanggan`),
  UNIQUE KEY `nomor_kwh` (`nomor_kwh`),
  KEY `id_tarif` (`id_tarif`),
  CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`id_tarif`) REFERENCES `tarif` (`id_tarif`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.pelanggan: ~0 rows (approximately)
INSERT INTO `pelanggan` (`id_pelanggan`, `username`, `password`, `nomor_kwh`, `nama_pelanggan`, `alamat`, `id_tarif`) VALUES
	(3, 'muni', 'muniganteng', '234532', 'Muqniansyah Arifin', 'jalan bahagia', 7);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.pembayaran: ~0 rows (approximately)
INSERT INTO `pembayaran` (`id_pembayaran`, `id_tagihan`, `id_tarif`, `tanggal_pembayaran`, `bulan_bayar`, `tahun_bayar`, `biaya_admin`, `total_bayar`, `id_user`, `bukti_bayar`, `status_bayar`) VALUES
	(8, 6, NULL, '2025-07-20', '0', NULL, NULL, 12100000.00, NULL, 'bukti_6_1753018686.jpg', 'sudah diverifikasi');

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
  CONSTRAINT `penggunaan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.penggunaan: ~0 rows (approximately)
INSERT INTO `penggunaan` (`id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `meter_awal`, `meter_akhir`) VALUES
	(6, 3, 'januari', '2012', 1232, 21232);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.tagihan: ~0 rows (approximately)
INSERT INTO `tagihan` (`id_tagihan`, `id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `jumlah_meter`, `status`) VALUES
	(6, 6, 3, 'januari', '2012', 20000, 'sudah dibayar');

-- Dumping structure for table db_listrik.tarif
CREATE TABLE IF NOT EXISTS `tarif` (
  `id_tarif` int NOT NULL AUTO_INCREMENT,
  `daya` varchar(20) DEFAULT NULL,
  `tarifperkwh` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_tarif`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.tarif: ~0 rows (approximately)
INSERT INTO `tarif` (`id_tarif`, `daya`, `tarifperkwh`) VALUES
	(6, '450', 415.00),
	(7, '900', 605.00),
	(8, '1300', 1478.00),
	(9, '2200', 1467.00);

-- Dumping structure for table db_listrik.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_admin` varchar(100) DEFAULT NULL,
  `id_level` int DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `id_level` (`id_level`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `level` (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_listrik.user: ~1 rows (approximately)
INSERT INTO `user` (`id_user`, `username`, `password`, `nama_admin`, `id_level`) VALUES
	(1, 'admin', 'admin123', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
