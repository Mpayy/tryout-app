<?php

namespace App\Services;

use App\Models\PaketUjian;
use App\Models\Soal;
use Illuminate\Support\Facades\DB;

class PaketUjianService
{
    public function createPaketUjian(array $data, int $guruId)
    {
        return DB::transaction(function () use ($data, $guruId) {
            $paket = PaketUjian::create([
                'nama' => $data['nama'],
                'mata_pelajaran_id'=> $data['mata_pelajaran_id'],
                'durasi' => $data['durasi'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'status' => $data['status'] ?? 'draft',
                'guru_id' => $guruId,
            ]);
            $paket->kelas()->attach($data['kelas_ids']);

            return $paket;
        });
    }

    public function updatePaketUjian(PaketUjian $paket, array $data)
    {
        return DB::transaction(function () use ($paket,$data){
            $paket->update([
                'nama' => $data['nama'],
                'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                'durasi' => $data['durasi'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'status' => $data['status'] ?? 'draft',
            ]);

            $paket->kelas()->sync($data['kelas_ids']);
        });
    }

    public function addSoalToPaket(PaketUjian $paket, array $soalIds)
    {
        return DB::transaction(function () use ($paket,$soalIds){
            $lastOrder = DB::table('paket_ujian_soal')->where('paket_ujian_id', $paket->id)->max('nomor_urut') ?? 0;

            $attachData =[];
            foreach ($soalIds as $soalId){
                $lastOrder++;
                $attachData[$soalId] = ['nomor_urut' => $lastOrder];
            }

            $paket->soal()->attach($attachData);
        });
    }

    public function deleteSoalFromPaket(PaketUjian $paket, Soal $soal)
    {
        return DB::transaction(function () use ($paket,$soal){
            $paket->soal()->detach($soal->id);

            $remainingSoal = DB::table('paket_ujian_soal')->where('paket_ujian_id', $paket->id)->orderBy('nomor_urut', 'asc')->get();

            foreach ($remainingSoal as $index => $row) {
                DB::table('paket_ujian_soal')->where('id', $row->id)->update(['nomor_urut'=> $index + 1]);
            }
        });
    }

    public function updateStatusPaket(PaketUjian $paket, string $status)
    {
        $paket->update(['status'=>$status]);
    }

    
}