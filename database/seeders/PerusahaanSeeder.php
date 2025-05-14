<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('perusahaan')->insert([
            [
                'nama_perusahaan' => 'PT Maju Bersama Indonesia',
                'alamat' => 'Jl. Sudirman No. 123, Kuningan',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'bidang_usaha' => 'Teknologi Informasi',
                'no_telp' => '0217891234',
                'email' => 'info@majubersama.co.id',
                'deskripsi' => 'Perusahaan teknologi informasi yang berfokus pada pengembangan perangkat lunak dan konsultasi IT.',
                'logo_path' => 'logo/maju_bersama.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perusahaan' => 'PT Global Teknologi Nusantara',
                'alamat' => 'Jl. Gatot Subroto No. 456, Setiabudi',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'bidang_usaha' => 'Digital Agency',
                'no_telp' => '0217892345',
                'email' => 'contact@gtn.co.id',
                'deskripsi' => 'Digital agency yang menyediakan layanan pengembangan web, mobile, dan pemasaran digital.',
                'logo_path' => 'logo/global_teknologi.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perusahaan' => 'PT Cyber Indonesia',
                'alamat' => 'Jl. TB Simatupang No. 789, Cilandak',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'bidang_usaha' => 'Cyber Security',
                'no_telp' => '0217893456',
                'email' => 'info@cyberindonesia.co.id',
                'deskripsi' => 'Perusahaan keamanan siber yang menyediakan layanan konsultasi, audit, dan pelatihan keamanan jaringan.',
                'logo_path' => 'logo/cyber_indonesia.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perusahaan' => 'PT Data Analitika Indonesia',
                'alamat' => 'Jl. Casablanca No. 88, Tebet',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'bidang_usaha' => 'Data Analytics',
                'no_telp' => '0217894567',
                'email' => 'contact@dataanalitika.co.id',
                'deskripsi' => 'Perusahaan analitika data yang menyediakan layanan pengolahan big data, machine learning, dan business intelligence.',
                'logo_path' => 'logo/data_analitika.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_perusahaan' => 'PT Sinar Digital Nusantara',
                'alamat' => 'Jl. Rasuna Said No. 55, Kuningan',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'bidang_usaha' => 'E-Commerce',
                'no_telp' => '0217895678',
                'email' => 'careers@sinardigital.id',
                'deskripsi' => 'Platform e-commerce yang memiliki divisi teknologi informasi untuk pengembangan platform online dan sistem logistik.',
                'logo_path' => 'logo/sinar_digital.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
