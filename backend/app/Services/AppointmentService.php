<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentService
{
    public function create(array $data): Appointment
    {
        $service  = Service::findOrFail($data['service_id']);
        $startsAt = Carbon::parse($data['starts_at']);

        return Appointment::create([
            ...$data,
            'starts_at' => $startsAt,
            'ends_at'   => $startsAt->copy()->addMinutes($service->duration),
            'status'    => 'pending',
        ]);
    }

    public function update(Appointment $appointment, array $data): Appointment
    {
        if (isset($data['starts_at']) || isset($data['service_id'])) {
            $serviceId = $data['service_id'] ?? $appointment->service_id;
            $startsAt  = Carbon::parse($data['starts_at'] ?? $appointment->starts_at);
            $service   = Service::findOrFail($serviceId);

            $data['starts_at'] = $startsAt;
            $data['ends_at']   = $startsAt->copy()->addMinutes($service->duration);
        }

        $appointment->update($data);
        return $appointment->fresh();
    }
}
