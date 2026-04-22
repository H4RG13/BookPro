<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'client_id'  => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'starts_at'  => 'required|date|after:now',
            'notes'      => 'nullable|string',
        ];
    }
}
