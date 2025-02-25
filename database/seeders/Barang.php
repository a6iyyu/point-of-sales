<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Barang extends Seeder
{
    public function run(): void
    {
        DB::table('m_barang')->insert([
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Laptop ASUS ROG',
                'harga_beli' => 15_000_000,
                'harga_jual' => 17_500_000
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'BRG002',
                'barang_nama' => 'MacBook Air M2',
                'harga_beli' => 18_000_000,
                'harga_jual' => 20_500_000
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 1,
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Monitor 27 inch IPS',
                'harga_beli' => 3_500_000,
                'harga_jual' => 4_200_000
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2,
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Kaos Polos Katun',
                'harga_beli' => 50_000,
                'harga_jual' => 75_000
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 2,
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Jaket Denim',
                'harga_beli' => 200_000,
                'harga_jual' => 250_000
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 2,
                'barang_kode' => 'BRG006',
                'barang_nama' => 'Sepatu Sneakers',
                'harga_beli' => 400_000,
                'harga_jual' => 550_000
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 3,
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Roti Tawar',
                'harga_beli' => 15_000,
                'harga_jual' => 20_000
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 3,
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Biskuit Gandum',
                'harga_beli' => 25_000,
                'harga_jual' => 35_000
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 3,
                'barang_kode' => 'BRG009',
                'barang_nama' => 'Mie Instan',
                'harga_beli' => 3_500,
                'harga_jual' => 5_000
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 4,
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Air Mineral 1L',
                'harga_beli' => 5_000,
                'harga_jual' => 8_000
            ],
            [
                'barang_id' => 11,
                'kategori_id' => 4,
                'barang_kode' => 'BRG011',
                'barang_nama' => 'Jus Jeruk Botol',
                'harga_beli' => 12_000,
                'harga_jual' => 18_000
            ],
            [
                'barang_id' => 12,
                'kategori_id' => 4,
                'barang_kode' => 'BRG012',
                'barang_nama' => 'Kopi Bubuk 250g',
                'harga_beli' => 30_000,
                'harga_jual' => 40_000
            ],
            [
                'barang_id' => 13,
                'kategori_id' => 5,
                'barang_kode' => 'BRG013',
                'barang_nama' => 'Paracetamol 500mg',
                'harga_beli' => 2_500,
                'harga_jual' => 5_000
            ],
            [
                'barang_id' => 14,
                'kategori_id' => 5,
                'barang_kode' => 'BRG014',
                'barang_nama' => 'Vitamin C 1000mg',
                'harga_beli' => 10_000,
                'harga_jual' => 15_000
            ],
            [
                'barang_id' => 15,
                'kategori_id' => 5,
                'barang_kode' => 'BRG015',
                'barang_nama' => 'Antiseptik Cair 100ml',
                'harga_beli' => 18_000,
                'harga_jual' => 25_000
            ],
        ]);
    }
}