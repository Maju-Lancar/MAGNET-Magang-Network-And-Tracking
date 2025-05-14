<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('program_studi')->insert([
            [
                'nama_program' => 'Teknik Informatika',
                'jenjang' => 'D4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_program' => 'Sistem Informasi Bisnis',
                'jenjang' => 'D4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_program' => 'Pengembangan Perangkat Lunak Situs',
                'jenjang' => 'D2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
