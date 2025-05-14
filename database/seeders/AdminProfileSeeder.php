<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminProfileSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admin_profiles')->insert([
            [
                'user_id' => 1, // Budi Santoso
                'nip' => '198603172008011005',
                'no_hp' => '081456789012',
                'foto_path' => 'foto/budi_santoso.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2, // Indah Permata
                'nip' => '198705192010012006',
                'no_hp' => '081456789013',
                'foto_path' => 'foto/indah_permata.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
