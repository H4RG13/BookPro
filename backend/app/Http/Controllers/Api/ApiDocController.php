<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'BookPro API',
    description: 'Booking management API built with Laravel + Sanctum',
    contact: new OA\Contact(email: 'admin@bookpro.test'),
)]
#[OA\Server(url: 'http://localhost:8000/api', description: 'Local development')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Enter the Bearer token returned by /login or /register',
)]

// ── Reusable schemas ──────────────────────────────────────────────────────────

#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'id',    type: 'integer', example: 1),
        new OA\Property(property: 'name',  type: 'string',  example: 'Alice Smith'),
        new OA\Property(property: 'email', type: 'string',  format: 'email', example: 'alice@example.com'),
    ]
)]
#[OA\Schema(
    schema: 'AuthResponse',
    properties: [
        new OA\Property(property: 'token', type: 'string', example: '1|abc123...'),
        new OA\Property(property: 'user',  ref: '#/components/schemas/User'),
    ]
)]
#[OA\Schema(
    schema: 'Client',
    properties: [
        new OA\Property(property: 'id',    type: 'integer', example: 1),
        new OA\Property(property: 'name',  type: 'string',  example: 'Bob Jones'),
        new OA\Property(property: 'email', type: 'string',  format: 'email', example: 'bob@example.com'),
        new OA\Property(property: 'phone', type: 'string',  example: '555-0101', nullable: true),
        new OA\Property(property: 'notes', type: 'string',  nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[OA\Schema(
    schema: 'Service',
    properties: [
        new OA\Property(property: 'id',          type: 'integer', example: 1),
        new OA\Property(property: 'name',         type: 'string',  example: 'Haircut'),
        new OA\Property(property: 'duration',     type: 'integer', description: 'Duration in minutes', example: 30),
        new OA\Property(property: 'price',        type: 'number',  format: 'float', example: 25.00),
        new OA\Property(property: 'description',  type: 'string',  nullable: true),
        new OA\Property(property: 'created_at',   type: 'string',  format: 'date-time'),
        new OA\Property(property: 'updated_at',   type: 'string',  format: 'date-time'),
    ]
)]
#[OA\Schema(
    schema: 'Appointment',
    properties: [
        new OA\Property(property: 'id',         type: 'integer', example: 1),
        new OA\Property(property: 'client_id',  type: 'integer', example: 1),
        new OA\Property(property: 'service_id', type: 'integer', example: 2),
        new OA\Property(property: 'starts_at',  type: 'string',  format: 'date-time'),
        new OA\Property(property: 'ends_at',    type: 'string',  format: 'date-time'),
        new OA\Property(property: 'status',     type: 'string',  enum: ['pending', 'confirmed', 'cancelled', 'completed']),
        new OA\Property(property: 'notes',      type: 'string',  nullable: true),
        new OA\Property(property: 'client',     ref: '#/components/schemas/Client',  nullable: true),
        new OA\Property(property: 'service',    ref: '#/components/schemas/Service', nullable: true),
        new OA\Property(property: 'created_at', type: 'string',  format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string',  format: 'date-time'),
    ]
)]
#[OA\Schema(
    schema: 'ValidationError',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The email field is required.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string')
            )
        ),
    ]
)]
class ApiDocController {}
