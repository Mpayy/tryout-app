<?php

namespace App\Services;

use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Support\Facades\DB;

class SoalService
{
    public function createSoal(int $mapelId, array $listSoal, int $guruId): array
    {
        return DB::transaction(function () use ($mapelId, $listSoal, $guruId) {
            $createdSoals = [];

            foreach ($listSoal as $data) {
                $newSoal = Soal::create([
                    'mata_pelajaran_id' => $mapelId,
                    'guru_id' => $guruId,
                    'konten' => $data['pertanyaan'],
                ]);

                $opsiJawaban = [];
                foreach ($data['opsi'] as $label => $konten) {
                    $opsiJawaban[] = [
                        'label' => $label,
                        'konten' => $konten,
                        'is_correct' => $data['jawaban_benar'] === $label,
                    ];
                }

                $newSoal->pilihanJawaban()->createMany($opsiJawaban);

                $createdSoals[] = $newSoal;
            }

            return $createdSoals;
        });
    }

    public function updateSoal(Soal $soal, array $data): Soal
    {
        return DB::transaction(function () use ($soal, $data) {
            // 1. Update data soal utama
            $soal->update([
                'konten' => $data['pertanyaan'],
            ]);

            // 2. Petakan data opsi jawaban baru
            $opsiData = [
                'A' => $data['opsi_a'],
                'B' => $data['opsi_b'],
                'C' => $data['opsi_c'],
                'D' => $data['opsi_d'],
            ];

            // 3. Update setiap pilihan jawaban yang berelasi
            // Pastikan relasi 'pilihanJawaban' sudah di-load
            foreach ($soal->pilihanJawaban as $jawaban) {
                $jawabanBaru  = $opsiData[$jawaban->label];
                $apakahBenar = ($jawaban->label === $data['jawaban_benar']) ? 1 : 0;

                $jawaban->update([
                    'konten'     => $jawabanBaru,
                    'is_correct' => $apakahBenar
                ]);
            }

            return $soal;
        });
    }
}
