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
        Schema::create('sesi_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('paket_ujian_id')->constrained('paket_ujian')->cascadeOnDelete();
            $table->string('token', 100)->unique();
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->integer('sisa_waktu')->nullable();
            $table->enum('status', ['menunggu', 'berlangsung', 'selesai', 'timeout'])->default('menunggu');
            $table->integer('jumlah_pelanggaran')->default(0);
            $table->decimal('nilai', 5, 2)->nullable();
            $table->integer('total_benar')->default(0);
            $table->integer('total_salah')->default(0);
            $table->integer('total_ragu')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_ujian');
    }
};
