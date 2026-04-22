<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:clients,email,' . $this->route('client')->id,
            'phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
        ];
    }
}
