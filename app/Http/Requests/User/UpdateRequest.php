<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Mengambil ID user dari route (bisa berupa object $this->user atau parameter $this->route('user'))
        $userId = $this->user ? $this->user->id : $this->route('user');

        return [
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                // Abaikan pengecekan unique untuk ID user yang sedang di-edit saat ini
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'nullable|string|min:6',
            'role_id'  => 'required|exists:roles,id',
        ];
    }
}
