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
        Schema::table('final_rank_recommendation', function (Blueprint $table) {
            $table->dropForeign(['fmf_id']);
            $table->dropColumn(['fmf_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('final_rank_recommendation', function (Blueprint $table) {
            $table->foreignId('fmf_id')->constrained('full_multiplicative_form')->onDelete('cascade');
        });
    }
};
