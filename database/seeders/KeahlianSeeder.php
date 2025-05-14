<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeahlianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('keahlian')->insert([
            [
                'nama_keahlian' => 'Web Development',
                'deskripsi' => 'Keahlian dalam pengembangan aplikasi berbasis web menggunakan HTML, CSS, JavaScript, dan framework populer seperti Laravel, React, dan Vue.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Mobile Development',
                'deskripsi' => 'Keahlian dalam pengembangan aplikasi mobile untuk Android dan iOS menggunakan Java, Kotlin, Swift, atau React Native.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'UI/UX Design',
                'deskripsi' => 'Keahlian dalam merancang antarmuka pengguna dan pengalaman pengguna untuk aplikasi web dan mobile.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Data Science',
                'deskripsi' => 'Keahlian dalam analisis data, pemodelan statistik, machine learning, dan visualisasi data.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Database Management',
                'deskripsi' => 'Keahlian dalam mengelola database relasional (MySQL, PostgreSQL) dan non-relasional (MongoDB, Redis).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'DevOps',
                'deskripsi' => 'Keahlian dalam otomatisasi deployment, integrasi berkelanjutan, dan pengelolaan infrastruktur.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Digital Marketing',
                'deskripsi' => 'Keahlian dalam strategi pemasaran digital, SEO, SEM, media sosial, dan analitik web.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_keahlian' => 'Network Security',
                'deskripsi' => 'Keahlian dalam pengamanan jaringan, deteksi intrusi, dan keamanan siber.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
