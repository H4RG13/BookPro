<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use OpenApi\Attributes as OA;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentService $appointmentService) {}

    #[OA\Get(
        path: '/appointments',
        summary: 'List all appointments (with client & service)',
        tags: ['Appointments'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Array of appointments',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Appointment'))
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index()
    {
        return response()->json(
            Appointment::with(['client', 'service'])->orderBy('starts_at')->get()
        );
    }

    #[OA\Post(
        path: '/appointments',
        summary: 'Book a new appointment',
        description: 'The `ends_at` time is calculated automatically from the service duration.',
        tags: ['Appointments'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['client_id', 'service_id', 'starts_at'],
                properties: [
                    new OA\Property(property: 'client_id',  type: 'integer', example: 1),
                    new OA\Property(property: 'service_id', type: 'integer', example: 2),
                    new OA\Property(property: 'starts_at',  type: 'string',  format: 'date-time', example: '2026-05-01T10:00:00'),
                    new OA\Property(property: 'notes',      type: 'string',  nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Appointment created', content: new OA\JsonContent(ref: '#/components/schemas/Appointment')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->create($request->validated());
        return response()->json($appointment->load(['client', 'service']), 201);
    }

    #[OA\Put(
        path: '/appointments/{id}',
        summary: 'Update an appointment',
        tags: ['Appointments'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'client_id',  type: 'integer', example: 1),
                    new OA\Property(property: 'service_id', type: 'integer', example: 2),
                    new OA\Property(property: 'starts_at',  type: 'string',  format: 'date-time'),
                    new OA\Property(property: 'status',     type: 'string',  enum: ['pending', 'confirmed', 'cancelled', 'completed']),
                    new OA\Property(property: 'notes',      type: 'string',  nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Appointment updated', content: new OA\JsonContent(ref: '#/components/schemas/Appointment')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment = $this->appointmentService->update($appointment, $request->validated());
        return response()->json($appointment->load(['client', 'service']));
    }
}
