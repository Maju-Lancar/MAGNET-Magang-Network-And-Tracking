<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferensiKeahlianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('preferensi_keahlian')->insert([
            [
                'mahasiswa_id' => 1, // Ahmad Fadli
                'keahlian_id' => 1, // Web Development
                'tingkat_keahlian' => 'menengah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 1, // Ahmad Fadli
                'keahlian_id' => 5, // Database Management
                'tingkat_keahlian' => 'pemula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 2, // Rina Fitriani
                'keahlian_id' => 7, // Digital Marketing
                'tingkat_keahlian' => 'menengah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 2, // Rina Fitriani
                'keahlian_id' => 3, // UI/UX Design
                'tingkat_keahlian' => 'pemula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 3, // Dimas Pratama
                'keahlian_id' => 2, // Mobile Development
                'tingkat_keahlian' => 'ahli',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 3, // Dimas Pratama
                'keahlian_id' => 6, // DevOps
                'tingkat_keahlian' => 'pemula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 4, // Anisa Rahma
                'keahlian_id' => 4, // Data Science
                'tingkat_keahlian' => 'menengah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 4, // Anisa Rahma
                'keahlian_id' => 8, // Network Security
                'tingkat_keahlian' => 'pemula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 5, // Fajar Nugroho
                'keahlian_id' => 5, // Database Management
                'tingkat_keahlian' => 'menengah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 5, // Fajar Nugroho
                'keahlian_id' => 1, // Web Development
                'tingkat_keahlian' => 'pemula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 6, // Ratna Dewi
                'keahlian_id' => 3, // UI/UX Design
                'tingkat_keahlian' => 'ahli',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 6, // Ratna Dewi
                'keahlian_id' => 7, // Digital Marketing
                'tingkat_keahlian' => 'menengah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
