<?php

use OpenApi\Attributes as OA;

class CategoryPaths
{
    #[OA\Get(path: '/categories', tags: ['Categories'], summary: 'List categories',
        responses: [new OA\Response(response: 200, description: 'Categories', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Category')))])]
    public function listCategories() {}

    #[OA\Get(path: '/categories/{id}', tags: ['Categories'], summary: 'Get a category',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Category', content: new OA\JsonContent(ref: '#/components/schemas/Category')),
            new OA\Response(response: 404, description: 'Category not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function getCategory() {}

    #[OA\Post(path: '/categories', tags: ['Categories'], summary: 'Create a category',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/CategoryInput')),
        responses: [new OA\Response(response: 201, description: 'Category created', content: new OA\JsonContent(ref: '#/components/schemas/Category')),
            new OA\Response(response: 400, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function createCategory() {}

    #[OA\Put(path: '/categories/{id}', tags: ['Categories'], summary: 'Replace a category',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/CategoryInput')),
        responses: [new OA\Response(response: 200, description: 'Category updated', content: new OA\JsonContent(ref: '#/components/schemas/Category'))])]
    public function replaceCategory() {}

    #[OA\Patch(path: '/categories/{id}', tags: ['Categories'], summary: 'Update a category',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/CategoryInput')),
        responses: [new OA\Response(response: 200, description: 'Category updated', content: new OA\JsonContent(ref: '#/components/schemas/Category'))])]
    public function updateCategory() {}

    #[OA\Delete(path: '/categories/{id}', tags: ['Categories'], summary: 'Delete a category',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Category deleted', content: new OA\JsonContent(ref: '#/components/schemas/DeleteResponse')),
            new OA\Response(response: 404, description: 'Category not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function deleteCategory() {}
}
