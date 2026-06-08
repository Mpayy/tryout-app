<?php

namespace App\Services;

use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Support\Facades\DB;

class SoalService
{
    public function createSoal(int $mapelId, array $listSoal): array
    {
        return DB::transaction(function () use ($mapelId, $listSoal) {
            $createdSoals = [];

            foreach ($listSoal as $data) {
                $newSoal = Soal::create([
                    'mata_pelajaran_id' => $mapelId,
                    'guru_id' => auth()->id(),
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

    public function updateSoal(Soal $soal, int $mapelId, array $listSoal): array
    {
        return DB::transaction(function () use ($soal, $mapelId, $listSoal) {
            $updatedSoals = [];

            foreach ($listSoal as $data) {
                $newSoal = Soal::where('id', $soalId)->update([
                    'mata_pelajaran_id' => $mapelId,
                    'guru_id' => auth()->id(),
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

                $updatedSoals[] = $newSoal;
            }

            return $updatedSoals;
        });
    }
}