<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Supplier extends Seeder
{
    public function run(): void
    {
        DB::table('m_supplier')->insert([
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'CV Maju Jaya',
                'supplier_alamat' => 'Jl. Merdeka No. 45, Jakarta'
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'PT Sumber Rezeki',
                'supplier_alamat' => 'Jl. Sudirman No. 12, Surabaya'
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'UD Sentosa',
                'supplier_alamat' => 'Jl. Diponegoro No. 78, Bandung'
            ],
        ]);
    }
}