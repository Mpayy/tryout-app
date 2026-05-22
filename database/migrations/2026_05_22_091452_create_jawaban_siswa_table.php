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
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_ujian_id')->constrained('sesi_ujian')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soal')->cascadeOnDelete();
            $table->foreignId('pilihan_jawaban_id')->nullable()->constrained('pilihan_jawaban')->nullOnDelete();
            $table->boolean('is_ragu')->default(false);
            $table->timestamps();
            // Satu siswa hanya boleh punya 1 jawaban per soal dalam 1 sesi
            $table->unique(['sesi_ujian_id', 'soal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};
