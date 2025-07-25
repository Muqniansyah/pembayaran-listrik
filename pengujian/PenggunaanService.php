<?php

class PenggunaanService {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function tambahPenggunaan($id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir) {
        $stmt1 = $this->conn->prepare("INSERT INTO penggunaan (id_pelanggan, bulan, tahun, meter_awal, meter_akhir) VALUES (?, ?, ?, ?, ?)");
        $stmt1->bind_param("issii", $id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir);
        $stmt1->execute();
        $id_penggunaan = $this->conn->insert_id;

        $jumlah_meter = $meter_akhir - $meter_awal;

        $stmt2 = $this->conn->prepare("INSERT INTO tagihan (id_penggunaan, id_pelanggan, bulan, tahun, jumlah_meter, status) VALUES (?, ?, ?, ?, ?, 'belum dibayar')");
        $stmt2->bind_param("iissi", $id_penggunaan, $id_pelanggan, $bulan, $tahun, $jumlah_meter);
        $stmt2->execute();

        return $id_penggunaan;
    }

    public function hapusPenggunaan($id_penggunaan, $id_pelanggan) {
        $stmt1 = $this->conn->prepare("DELETE FROM tagihan WHERE id_penggunaan=?");
        $stmt1->bind_param("i", $id_penggunaan);
        $stmt1->execute();

        $stmt2 = $this->conn->prepare("DELETE FROM penggunaan WHERE id_penggunaan=? AND id_pelanggan=?");
        $stmt2->bind_param("ii", $id_penggunaan, $id_pelanggan);
        $stmt2->execute();

        return true;
    }
}
