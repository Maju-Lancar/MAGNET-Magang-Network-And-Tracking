<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenProfileSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dosen_profiles')->insert([
            [
                'user_id' => 3, // Dr. Agus Wijaya
                'nip' => '198503121999031001',
                'program_studi_id' => 1, // Teknik Informatika
                'no_hp' => '081345678901',
                'foto_path' => 'foto/agus_wijaya.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4, // Prof. Siti Rahayu
                'nip' => '197804152001032002',
                'program_studi_id' => 2, // Sistem Informasi Bisnis
                'no_hp' => '081345678902',
                'foto_path' => 'foto/siti_rahayu.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5, // Dr. Bambang Hermawan
                'nip' => '198012232005041003',
                'program_studi_id' => 3, // PPLS
                'no_hp' => '081345678903',
                'foto_path' => 'foto/bambang_hermawan.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6, // Dr. Dewi Sulistyowati
                'nip' => '198209172007012004',
                'program_studi_id' => 1, // Teknik Informatika
                'no_hp' => '081345678904',
                'foto_path' => 'foto/dewi_sulistyowati.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
