<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vector_normalization', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('lowongan_magang_id')->constrained('lowongan_magang')->onDelete('cascade');
            $table->float('pekerjaan');
            $table->float('open_remote');
            $table->float('jenis_magang');
            $table->float('bidang_industri');
            $table->float('lokasi_magang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vector_normalization');
    }
};
