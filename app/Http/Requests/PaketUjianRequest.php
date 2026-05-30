<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaketUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
            'nama' => ['required', 'string', 'max:200'],
            'durasi' => ['required', 'integer', 'min:10'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'kelas_ids' => ['required', 'array', 'min:1'],
            'kelas_ids.*' => ['exists:kelas,id'],
            'acak_soal' => ['nullable', 'boolean'],
            'acak_jawaban' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:draft,aktif,selesai'],
        ];
    }
}
