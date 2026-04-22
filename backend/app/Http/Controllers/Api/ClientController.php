<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use OpenApi\Attributes as OA;

class ClientController extends Controller
{
    #[OA\Get(
        path: '/clients',
        summary: 'List all clients',
        tags: ['Clients'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Array of clients',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Client'))
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index()
    {
        return response()->json(Client::orderBy('name')->get());
    }

    #[OA\Post(
        path: '/clients',
        summary: 'Create a new client',
        tags: ['Clients'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email'],
                properties: [
                    new OA\Property(property: 'name',  type: 'string', example: 'Bob Jones'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'bob@example.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '555-0102', nullable: true),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Client created', content: new OA\JsonContent(ref: '#/components/schemas/Client')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());
        return response()->json($client, 201);
    }

    #[OA\Put(
        path: '/clients/{id}',
        summary: 'Update an existing client',
        tags: ['Clients'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name',  type: 'string', example: 'Bob Jones'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'bob@example.com'),
                    new OA\Property(property: 'phone', type: 'string', nullable: true),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Client updated', content: new OA\JsonContent(ref: '#/components/schemas/Client')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        return response()->json($client);
    }

    #[OA\Delete(
        path: '/clients/{id}',
        summary: 'Delete a client',
        tags: ['Clients'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Client deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(null, 204);
    }
}
