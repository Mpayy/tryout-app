<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MataPelajaranRequest extends FormRequest
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
        $mapelId = $this->route('mapel') ? $this->route('mapel')->id : null;
        return [
            'nama' => ['required', 'string', 'max:100'],
            'kode' => ['required', 'string', 'max:10', 'unique:mata_pelajaran,kode,' . $mapelId],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama mata pelajaran wajib diisi.',
            'nama.max' => 'Nama mata pelajaran maksimal 100 karakter.',
            'kode.required' => 'Kode mata pelajaran wajib diisi.',
            'kode.max' => 'Kode mata pelajaran maksimal 10 karakter.',
            'kode.unique' => 'Kode mata pelajaran sudah digunakan.',
            'deskripsi.max' => 'Deskripsi maksimal 255 karakter.',
        ];
    }
}
