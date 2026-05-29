<?php

namespace Database\Seeders;

use App\Models\PaketUjian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaketUjianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaketUjian::create([
            "guru_id" => 2,
            "mata_pelajaran_id" => 1,
            "nama" => "Tryout Soshum #1",
            "deskripsi" => "Deskripsi Tryout Soshum #1",
            "tanggal_mulai" => Carbon::now(),
            "tanggal_selesai" => Carbon::now()->addDays(7),
            "durasi" => 60,
            "status" => "draft",
            "acak_soal" => 0,
            "acak_jawaban" => 0,
        ]);
    }
}
