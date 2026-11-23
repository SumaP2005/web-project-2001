<?php

use OpenApi\Attributes as OA;

class UserPaths
{
    #[OA\Get(path: '/users', tags: ['Users'], summary: 'List users',
        responses: [new OA\Response(response: 200, description: 'Users', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/User'))),
            new OA\Response(response: 500, description: 'Server error', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function listUsers() {}

    #[OA\Get(path: '/users/by-email/{email}', tags: ['Users'], summary: 'Find a user by email',
        parameters: [new OA\Parameter(name: 'email', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'email'))],
        responses: [new OA\Response(response: 200, description: 'User', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function getUserByEmail() {}

    #[OA\Get(path: '/users/{id}', tags: ['Users'], summary: 'Get a user',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'User', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function getUser() {}

    #[OA\Post(path: '/users', tags: ['Users'], summary: 'Create a user',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UserInput')),
        responses: [new OA\Response(response: 201, description: 'User created', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 400, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function createUser() {}

    #[OA\Put(path: '/users/{id}', tags: ['Users'], summary: 'Replace a user',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UserInput')),
        responses: [new OA\Response(response: 200, description: 'User updated', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function replaceUser() {}

    #[OA\Patch(path: '/users/{id}', tags: ['Users'], summary: 'Update a user',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UserInput')),
        responses: [new OA\Response(response: 200, description: 'User updated', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function updateUser() {}

    #[OA\Delete(path: '/users/{id}', tags: ['Users'], summary: 'Delete a user',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'User deleted', content: new OA\JsonContent(ref: '#/components/schemas/DeleteResponse')),
            new OA\Response(response: 404, description: 'User not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function deleteUser() {}
}
