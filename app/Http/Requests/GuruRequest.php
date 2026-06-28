<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruRequest extends FormRequest
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
        $user = $this->route('user');
        $userId = $user instanceof \App\Models\User ? $user->id : $user;
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId, 'id')],
            'nip' => ['required', 'string', 'max:255', Rule::unique('profiles_guru', 'nip')->ignore($userId, 'user_id')],
            'role' => ['required', 'string', 'in:guru'],
            'mapel' => ['required', 'array', 'min:1'],
            'mapel.*' => ['integer', 'exists:mata_pelajaran,id'],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8'],
        ];
    }
}
