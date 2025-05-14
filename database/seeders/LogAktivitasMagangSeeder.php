<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogAktivitasMagangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('log_aktivitas_magang')->insert([
            [
                'pengajuan_id' => 1, // Ahmad Fadli - Web Developer
                'tanggal' => '2025-06-03',
                'aktivitas' => 'Orientasi dan pengenalan lingkungan kerja. Mendapatkan penjelasan tentang proyek yang akan dikerjakan selama magang.',
                'bukti_path' => 'bukti/log1_ahmad_fadli.pdf',
                'status' => 'disetujui',
                'catatan_dosen' => 'Aktivitas sesuai dengan rencana pembelajaran.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pengajuan_id' => 1, // Ahmad Fadli - Web Developer
                'tanggal' => '2025-06-10',
                'aktivitas' => 'Mempelajari codebase proyek dan mulai berkontribusi dengan memperbaiki bug kecil pada frontend aplikasi.',
                'bukti_path' => 'bukti/log2_ahmad_fadli.pdf',
                'status' => 'disetujui',
                'catatan_dosen' => 'Progres yang baik, terus tingkatkan pemahaman tentang struktur kode.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pengajuan_id' => 1, // Ahmad Fadli - Web Developer
                'tanggal' => '2025-06-17',
                'aktivitas' => 'Mengimplementasikan fitur login dan registrasi dengan Laravel Fortify.',
                'bukti_path' => 'bukti/log3_ahmad_fadli.pdf',
                'status' => 'revisi',
                'catatan_dosen' => 'Perlu menambahkan validasi yang lebih kuat pada form registrasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pengajuan_id' => 2, // Rina Fitriani - Digital Marketing
                'tanggal' => '2025-06-05',
                'aktivitas' => 'Orientasi dan pengenalan tim marketing. Mempelajari strategi marketing perusahaan dan tools yang digunakan.',
                'bukti_path' => 'bukti/log1_rina_fitriani.pdf',
                'status' => 'disetujui',
                'catatan_dosen' => 'Aktivitas sesuai dengan rencana pembelajaran.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pengajuan_id' => 2, // Rina Fitriani - Digital Marketing
                'tanggal' => '2025-06-12',
                'aktivitas' => 'Membantu pembuatan konten untuk media sosial dan menganalisis performa postingan sebelumnya.',
                'bukti_path' => 'bukti/log2_rina_fitriani.pdf',
                'status' => 'disetujui',
                'catatan_dosen' => 'Bagus, analisis yang dilakukan cukup detail.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pengajuan_id' => 4, // Anisa Rahma - Data Science
                'tanggal' => '2025-06-02',
                'aktivitas' => 'Orientasi dan pengenalan tim data science. Penjelasan tentang dataset yang akan dianalisis.',
                'bukti_path' => 'bukti/log1_anisa_rahma.pdf',
                'status' => 'disetujui',
                'catatan_dosen' => 'Aktivitas sesuai dengan rencana pembelajaran.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pengajuan_id' => 4, // Anisa Rahma - Data Science
                'tanggal' => '2025-06-09',
                'aktivitas' => 'Melakukan exploratory data analysis pada dataset pelanggan dan membuat visualisasi awal.',
                'bukti_path' => 'bukti/log2_anisa_rahma.pdf',
                'status' => 'disetujui',
                'catatan_dosen' => 'Visualisasi yang dibuat sangat informatif. Teruskan pekerjaan yang baik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pengajuan_id' => 6, // Ratna Dewi - UI/UX Designer
                'tanggal' => '2025-06-16',
                'aktivitas' => 'Orientasi dan pengenalan tim desain. Mempelajari design system perusahaan.',
                'bukti_path' => 'bukti/log1_ratna_dewi.pdf',
                'status' => 'diajukan',
                'catatan_dosen' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
