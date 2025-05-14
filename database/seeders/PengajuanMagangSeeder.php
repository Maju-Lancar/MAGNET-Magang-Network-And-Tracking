<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanMagangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengajuan_magang')->insert([
            [
                'mahasiswa_id' => 1, // Ahmad Fadli
                'lowongan_id' => 1, // Web Developer Internship
                'dosen_id' => 1, // Dr. Agus Wijaya
                'tanggal_pengajuan' => '2025-05-10',
                'tanggal_mulai_bimbingan' => '2025-06-01',
                'tanggal_selesai_bimbingan' => null,
                'status' => 'bimbingan_aktif',
                'catatan' => 'Pengajuan disetujui. Mahasiswa memiliki keterampilan yang sesuai dengan kebutuhan magang.',
                'surat_path' => 'surat/ahmad_fadli_surat_magang.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 2, // Rina Fitriani
                'lowongan_id' => 6, // Digital Marketing Internship
                'dosen_id' => 2, // Prof. Siti Rahayu
                'tanggal_pengajuan' => '2025-05-12',
                'tanggal_mulai_bimbingan' => '2025-06-01',
                'tanggal_selesai_bimbingan' => null,
                'status' => 'bimbingan_aktif',
                'catatan' => 'Pengajuan disetujui. Mahasiswa memiliki minat yang kuat di bidang digital marketing.',
                'surat_path' => 'surat/rina_fitriani_surat_magang.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 3, // Dimas Pratama
                'lowongan_id' => 5, // Mobile App Developer Internship
                'dosen_id' => 3, // Dr. Bambang Hermawan
                'tanggal_pengajuan' => '2025-05-15',
                'tanggal_mulai_bimbingan' => null,
                'tanggal_selesai_bimbingan' => null,
                'status' => 'diajukan',
                'catatan' => 'Menunggu konfirmasi perusahaan.',
                'surat_path' => 'surat/dimas_pratama_surat_magang.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 4, // Anisa Rahma
                'lowongan_id' => 4, // Data Science Internship
                'dosen_id' => 4, // Dr. Dewi Sulistyowati
                'tanggal_pengajuan' => '2025-05-05',
                'tanggal_mulai_bimbingan' => '2025-06-01',
                'tanggal_selesai_bimbingan' => null,
                'status' => 'bimbingan_aktif',
                'catatan' => 'Pengajuan disetujui. Mahasiswa memiliki kemampuan analisis data yang baik.',
                'surat_path' => 'surat/anisa_rahma_surat_magang.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 5, // Fajar Nugroho
                'lowongan_id' => 1, // Web Developer Internship
                'dosen_id' => 1, // Dr. Agus Wijaya
                'tanggal_pengajuan' => '2025-05-18',
                'tanggal_mulai_bimbingan' => null,
                'tanggal_selesai_bimbingan' => null,
                'status' => 'ditolak',
                'catatan' => 'Pengajuan ditolak karena kurangnya kesesuaian keterampilan dengan kebutuhan posisi.',
                'surat_path' => 'surat/fajar_nugroho_surat_magang.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 6, // Ratna Dewi
                'lowongan_id' => 2, // UI/UX Designer Internship
                'dosen_id' => 3, // Dr. Bambang Hermawan
                'tanggal_pengajuan' => '2025-05-08',
                'tanggal_mulai_bimbingan' => '2025-06-15',
                'tanggal_selesai_bimbingan' => null,
                'status' => 'bimbingan_aktif',
                'catatan' => 'Pengajuan disetujui. Mahasiswa memiliki portofolio desain yang mengesankan.',
                'surat_path' => 'surat/ratna_dewi_surat_magang.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
