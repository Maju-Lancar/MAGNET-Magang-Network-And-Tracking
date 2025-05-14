<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowonganMagangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lowongan_magang')->insert([
            [
                'perusahaan_id' => 1, // PT Maju Bersama Indonesia
                'judul' => 'Web Developer Internship',
                'deskripsi' => 'Magang sebagai Web Developer untuk pengembangan aplikasi berbasis web menggunakan Laravel dan React.',
                'tanggal_mulai' => '2025-06-01',
                'tanggal_selesai' => '2025-09-30',
                'kuota' => 3,
                'keahlian_utama' => 'PHP, Laravel, JavaScript, React',
                'persyaratan' => "1. Mahasiswa Teknik Informatika semester 5 ke atas\n2. Menguasai HTML, CSS, dan JavaScript\n3. Memiliki pengetahuan tentang framework Laravel\n4. Mampu bekerja dalam tim",
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => 2, // PT Global Teknologi Nusantara
                'judul' => 'UI/UX Designer Internship',
                'deskripsi' => 'Magang sebagai UI/UX Designer untuk merancang antarmuka pengguna dan pengalaman pengguna untuk aplikasi web dan mobile.',
                'tanggal_mulai' => '2025-06-15',
                'tanggal_selesai' => '2025-10-15',
                'kuota' => 2,
                'keahlian_utama' => 'Figma, Adobe XD, Prototyping, User Research',
                'persyaratan' => "1. Mahasiswa Sistem Informasi atau Teknik Informatika\n2. Menguasai Figma atau Adobe XD\n3. Memiliki portofolio desain\n4. Kreatif dan detail oriented",
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => 3, // PT Cyber Indonesia
                'judul' => 'Network Security Internship',
                'deskripsi' => 'Magang sebagai Network Security Analyst untuk membantu audit keamanan jaringan dan implementasi solusi keamanan.',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2025-12-31',
                'kuota' => 2,
                'keahlian_utama' => 'Network Security, Penetration Testing, Firewall Configuration',
                'persyaratan' => "1. Mahasiswa Teknik Informatika semester 6 ke atas\n2. Memahami konsep dasar keamanan jaringan\n3. Mengenal tools seperti Wireshark dan Metasploit\n4. Bersedia mengikuti pelatihan keamanan",
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => 4, // PT Data Analitika Indonesia
                'judul' => 'Data Science Internship',
                'deskripsi' => 'Magang sebagai Data Scientist untuk membantu analisis data, pengembangan model prediktif, dan visualisasi data.',
                'tanggal_mulai' => '2025-06-01',
                'tanggal_selesai' => '2025-11-30',
                'kuota' => 3,
                'keahlian_utama' => 'Python, R, SQL, Machine Learning, Data Visualization',
                'persyaratan' => "1. Mahasiswa Teknik Informatika atau Sistem Informasi\n2. Menguasai Python atau R untuk analisis data\n3. Memahami konsep machine learning dasar\n4. Mampu bekerja dengan database SQL",
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => 5, // PT Sinar Digital Nusantara
                'judul' => 'Mobile App Developer Internship',
                'deskripsi' => 'Magang sebagai Mobile App Developer untuk pengembangan aplikasi Android dan iOS menggunakan Flutter.',
                'tanggal_mulai' => '2025-07-15',
                'tanggal_selesai' => '2025-10-15',
                'kuota' => 2,
                'keahlian_utama' => 'Flutter, Dart, Firebase, REST API',
                'persyaratan' => "1. Mahasiswa Teknik Informatika atau Pengembangan Perangkat Lunak\n2. Memahami konsep mobile development\n3. Familiar dengan Flutter atau React Native\n4. Mampu mengintegrasikan API",
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => 2, // PT Global Teknologi Nusantara
                'judul' => 'Digital Marketing Internship',
                'deskripsi' => 'Magang sebagai Digital Marketing untuk membantu kampanye pemasaran digital, SEO, dan media sosial.',
                'tanggal_mulai' => '2025-06-01',
                'tanggal_selesai' => '2025-09-30',
                'kuota' => 2,
                'keahlian_utama' => 'SEO, SEM, Social Media, Content Marketing',
                'persyaratan' => "1. Mahasiswa Sistem Informasi Bisnis\n2. Mengerti konsep dasar digital marketing\n3. Kreatif dalam pembuatan konten\n4. Memahami analitik media sosial",
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
