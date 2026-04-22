<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use OpenApi\Attributes as OA;

class ServiceController extends Controller
{
    #[OA\Get(
        path: '/services',
        summary: 'List all services',
        tags: ['Services'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Array of services',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Service'))
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index()
    {
        return response()->json(Service::orderBy('name')->get());
    }

    #[OA\Post(
        path: '/services',
        summary: 'Create a new service',
        tags: ['Services'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'duration', 'price'],
                properties: [
                    new OA\Property(property: 'name',        type: 'string',  example: 'Deep Tissue Massage'),
                    new OA\Property(property: 'duration',    type: 'integer', description: 'Duration in minutes', example: 60),
                    new OA\Property(property: 'price',       type: 'number',  format: 'float', example: 75.00),
                    new OA\Property(property: 'description', type: 'string',  nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Service created', content: new OA\JsonContent(ref: '#/components/schemas/Service')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());
        return response()->json($service, 201);
    }
}
