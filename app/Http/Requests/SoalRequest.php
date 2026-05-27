<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SoalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'soal' => 'required|array|min:1',
            'soal.*.pertanyaan' => 'required|string',
            'soal.*.opsi.A' => 'required|string',
            'soal.*.opsi.B' => 'required|string',
            'soal.*.opsi.C' => 'required|string',
            'soal.*.opsi.D' => 'required|string',
            'soal.*.jawaban_benar' => 'required|string|in:A,B,C,D',
            'tingkat_kesulitan' => 'nullable|in:mudah,sedang,sulit',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'mapel_id.required' => 'Mata pelajaran harus dipilih.',
            'soal.required' => 'Anda harus memasukkan setidaknya satu soal.',
            'soal.*.pertanyaan.required' => 'Pertanyaan tidak boleh kosong.',
            'soal.*.opsi.A.required' => 'Pilihan A tidak boleh kosong.',
            'soal.*.opsi.B.required' => 'Pilihan B tidak boleh kosong.',
            'soal.*.opsi.C.required' => 'Pilihan C tidak boleh kosong.',
            'soal.*.opsi.D.required' => 'Pilihan D tidak boleh kosong.',
            'soal.*.jawaban_benar.in' => 'Kunci jawaban harus A, B, C, atau D.',
        ];
    }
}
