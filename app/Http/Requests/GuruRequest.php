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
        $userId = $this->route('user') ? $this->route('user')->id : null;
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId, 'id')],
            'nip' => ['required', 'string', 'max:255', Rule::unique('profiles_guru', 'nip')->ignore($userId, 'user_id')],
            'role' => ['required', 'string', 'exists:roles,name'],
            'bidang_studi' => ['required', 'string', 'max:255'],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8'],
        ];
    }
}
