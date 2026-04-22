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
        // Track reschedule: if starts_at is changing, save the old time and reset status
        if (isset($data['starts_at'])) {
            $newStartsAt = Carbon::parse($data['starts_at']);
            if (!$newStartsAt->eq($appointment->starts_at)) {
                $data['rescheduled_from'] = $appointment->starts_at;
                // Only reset status if not explicitly being set in this request
                if (!isset($data['status'])) {
                    $data['status'] = 'pending';
                }
            }
        }

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

    public function autoComplete(): int
    {
        return Appointment::where(function ($q) {
                $q->where('status', 'pending')->orWhere('status', 'confirmed');
            })
            ->where('ends_at', '<', now())
            ->update(['status' => 'completed']);
    }
}
