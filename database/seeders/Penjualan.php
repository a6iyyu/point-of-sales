<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Penjualan extends Seeder
{
    public function run(): void
    {
        $penjualan = [];

        for ($i = 1; $i <= 15; $i++) {
            $penjualan[] = [
                'penjualan_id' => $i,
                'pembeli' => Factory::create()->unique()->name(),
                'penjualan_kode' => Factory::create()->unique()->word(),
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30))->toDateTimeString(),
                'user_id' => rand(1, 3),
            ];
        }

        DB::table('t_penjualan')->insert($penjualan);
    }
}