<?php
// database/migrations/2026_05_23_000002_fix_is_correct_default_in_pilihan_jawaban.php
// FIX #2: Ubah default is_correct dari true menjadi false
// Default true menyebabkan SEMUA jawaban dianggap benar secara default
// yang membuat sistem scoring ujian rusak total.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah default kolom is_correct menjadi false
        Schema::table('pilihan_jawaban', function (Blueprint $table) {
            $table->boolean('is_correct')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pilihan_jawaban', function (Blueprint $table) {
            $table->boolean('is_correct')->default(true)->change();
        });
    }
};
