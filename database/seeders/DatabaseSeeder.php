<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProgramStudiSeeder::class,
            UserSeeder::class,
            MahasiswaProfileSeeder::class,
            DosenProfileSeeder::class,
            AdminProfileSeeder::class,
            KeahlianSeeder::class,
            PreferensiKeahlianSeeder::class,
            PerusahaanSeeder::class,
            LowonganMagangSeeder::class,
            PengajuanMagangSeeder::class,
            LogAktivitasMagangSeeder::class,
            UlasanMagangSeeder::class,
        ]);
    }
}
