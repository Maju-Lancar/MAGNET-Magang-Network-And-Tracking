<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UlasanMagangSeeder extends Seeder
{
    public function run(): void
    {
        // Belum ada ulasan karena magang masih berlangsung
        DB::table('ulasan_magang')->insert([
            [
                'pengajuan_id' => 5, // Fajar Nugroho (ditolak)
                'rating' => 2,
                'ulasan' => 'Proses seleksi cukup panjang dan kurang transparan. Feedback yang diberikan untuk penolakan juga kurang detail sehingga sulit untuk melakukan perbaikan di kesempatan berikutnya.',
                'is_published' => true,
                'is_anonymous' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
