<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'client_id'  => 'sometimes|exists:clients,id',
            'service_id' => 'sometimes|exists:services,id',
            'starts_at'  => 'sometimes|date',
            'status'     => 'sometimes|in:pending,confirmed,cancelled,completed',
            'notes'      => 'nullable|string',
        ];
    }
}
