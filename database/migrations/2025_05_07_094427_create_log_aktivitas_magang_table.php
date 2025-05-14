<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa_profiles')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan_magang')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen_profiles')->onDelete('cascade');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_mulai_bimbingan')->nullable();
            $table->date('tanggal_selesai_bimbingan')->nullable();
            $table->enum('status', ['diajukan', 'diterima', 'ditolak', 'bimbingan_aktif', 'bimbingan_selesai']);
            $table->text('catatan')->nullable();
            $table->string('surat_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magang');
    }
};
