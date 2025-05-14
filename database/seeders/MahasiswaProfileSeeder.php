<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaProfileSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mahasiswa_profiles')->insert([
            [
                'user_id' => 7, // Ahmad Fadli
                'nim' => '20210001',
                'program_studi_id' => 1, // Teknik Informatika
                'semester' => 6,
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
                'cv_path' => 'cv/ahmad_fadli.pdf',
                'foto_path' => 'foto/ahmad_fadli.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8, // Rina Fitriani
                'nim' => '20210002',
                'program_studi_id' => 2, // Sistem Informasi Bisnis
                'semester' => 6,
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Mawar No. 45, Jakarta Timur',
                'cv_path' => 'cv/rina_fitriani.pdf',
                'foto_path' => 'foto/rina_fitriani.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9, // Dimas Pratama
                'nim' => '20210003',
                'program_studi_id' => 3, // PPLS
                'semester' => 4,
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Cendana No. 78, Jakarta Utara',
                'cv_path' => 'cv/dimas_pratama.pdf',
                'foto_path' => 'foto/dimas_pratama.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10, // Anisa Rahma
                'nim' => '20210004',
                'program_studi_id' => 1, // Teknik Informatika
                'semester' => 6,
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Kenanga No. 12, Jakarta Barat',
                'cv_path' => 'cv/anisa_rahma.pdf',
                'foto_path' => 'foto/anisa_rahma.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 11, // Fajar Nugroho
                'nim' => '20210005',
                'program_studi_id' => 2, // Sistem Informasi Bisnis
                'semester' => 7,
                'no_hp' => '081234567894',
                'alamat' => 'Jl. Melati No. 34, Depok',
                'cv_path' => 'cv/fajar_nugroho.pdf',
                'foto_path' => 'foto/fajar_nugroho.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 12, // Ratna Dewi
                'nim' => '20210006',
                'program_studi_id' => 3, // PPLS
                'semester' => 7,
                'no_hp' => '081234567895',
                'alamat' => 'Jl. Anggrek No. 56, Tangerang',
                'cv_path' => 'cv/ratna_dewi.pdf',
                'foto_path' => 'foto/ratna_dewi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
