<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void {
        $this->call([
            Kategori::class,
            Barang::class,
            Level::class,
            User::class,
            Penjualan::class,
            PenjualanDetail::class,
            Supplier::class,
            Stok::class,
        ]);
    }
}