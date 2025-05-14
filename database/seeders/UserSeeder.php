<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Users
        DB::table('users')->insert([
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.admin@universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Indah Permata',
                'email' => 'indah.admin@universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Dosen Users
        DB::table('users')->insert([
            [
                'name' => 'Dr. Agus Wijaya',
                'email' => 'agus.wijaya@universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Prof. Siti Rahayu',
                'email' => 'siti.rahayu@universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Bambang Hermawan',
                'email' => 'bambang.h@universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Dewi Sulistyowati',
                'email' => 'dewi.s@universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Mahasiswa Users
        DB::table('users')->insert([
            [
                'name' => 'Ahmad Fadli',
                'email' => 'ahmad.fadli@mhs.universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rina Fitriani',
                'email' => 'rina.fitriani@mhs.universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas.p@mhs.universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anisa Rahma',
                'email' => 'anisa.r@mhs.universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fajar Nugroho',
                'email' => 'fajar.n@mhs.universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ratna Dewi',
                'email' => 'ratna.d@mhs.universitasprimagraha.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
