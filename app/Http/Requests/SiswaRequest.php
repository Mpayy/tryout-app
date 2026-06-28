<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaRequest extends FormRequest
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
        $user = $this->route("user");
        $userId = $user instanceof \App\Models\User ? $user->id : $user;
        return [
            "name"     => ["required", "string", "max:255"],
            "email"    => ["required", "email", Rule::unique("users", "email")->ignore($userId, "id")],
            "nis"      => ["required", "string", "max:10", Rule::unique("profiles_siswa", "nis")->ignore($userId, "user_id")],
            "role"     => ["required", "string", "in:siswa"],
            "kelas"    => ["required", "exists:kelas,id"],
            "jurusan"  => ["nullable", "string", "max:50"],
            "password" => [$this->isMethod('post') ? 'required' : 'nullable', "string", "min:8"],
        ];
    }
}
