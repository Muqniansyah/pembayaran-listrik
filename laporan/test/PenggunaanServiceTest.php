<?php
require_once __DIR__ . '/../pengujian/PenggunaanService.php';

use PHPUnit\Framework\TestCase;

class PenggunaanServiceTest extends TestCase {
    private $conn;
    private $service;

    protected function setUp(): void {
        $this->conn = new mysqli("localhost", "root", "", "db_listrik"); // Ganti jika database kamu beda
        $this->service = new PenggunaanService($this->conn);
    }

    public function testTambahPenggunaan() {
        $id_pelanggan = 4; // Ganti ke ID yang valid di database
        $bulan = 'Juli';
        $tahun = 2025;
        $awal = 100;
        $akhir = 150;

        $id = $this->service->tambahPenggunaan($id_pelanggan, $bulan, $tahun, $awal, $akhir);
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
    }

    public function testHapusPenggunaan() {
        $id_pelanggan = 4; // Ganti ke ID yang valid di database
        $id = $this->service->tambahPenggunaan($id_pelanggan, 'Agustus', 2025, 200, 250);
        $deleted = $this->service->hapusPenggunaan($id, $id_pelanggan);
        $this->assertTrue($deleted);
    }
}

// jalankan dengan : vendor/bin/phpunit test/PenggunaanServiceTest.php