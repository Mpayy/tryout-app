<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\MataPelajaran;

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
        $mapel = $this->route('mapel');
        $mapelId = $mapel instanceof MataPelajaran ? $mapel->id : $mapel;
        return [
            'nama' => ['required', 'string', 'max:100'],
            'kode' => ['required', 'string', 'max:10', Rule::unique('mata_pelajaran', 'kode')->ignore($mapelId, 'id')],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ];
    }
}
