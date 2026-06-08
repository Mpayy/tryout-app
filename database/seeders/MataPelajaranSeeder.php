<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MataPelajaran::create([
            'nama' => 'Matematika',
            'kode' => 'MTK',
            'deskripsi' => 'Mata pelajaran matematika'
        ]);
        MataPelajaran::create([
            'nama' => 'Bahasa Indonesia',
            'kode' => 'B.Indo',
            'deskripsi' => 'Mata pelajaran Bahasa Indonesia'
        ]);
        MataPelajaran::create([
            'nama' => 'Bahasa Inggris',
            'kode' => 'B.Ing',
            'deskripsi' => 'Mata pelajaran Bahasa Inggris'
        ]);
    }
}
