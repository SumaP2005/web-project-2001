<?php

use OpenApi\Attributes as OA;

class ItemPaths
{
    #[OA\Get(path: '/items', tags: ['Items'], summary: 'List items',
        responses: [new OA\Response(response: 200, description: 'Items', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Item')))])]
    public function listItems() {}
    #[OA\Get(path: '/items/by-seller/{id}', tags: ['Items'], summary: 'List items by seller',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Items', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Item')))])]
    public function listItemsBySeller() {}
    #[OA\Get(path: '/items/by-category/{id}', tags: ['Items'], summary: 'List items by category',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Items', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Item')))])]
    public function listItemsByCategory() {}
    #[OA\Get(path: '/items/{id}', tags: ['Items'], summary: 'Get an item',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Item', content: new OA\JsonContent(ref: '#/components/schemas/Item')),
            new OA\Response(response: 404, description: 'Item not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function getItem() {}
    #[OA\Post(path: '/items', tags: ['Items'], summary: 'Create an item',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ItemInput')),
        responses: [new OA\Response(response: 201, description: 'Item created', content: new OA\JsonContent(ref: '#/components/schemas/Item')),
            new OA\Response(response: 400, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function createItem() {}
    #[OA\Put(path: '/items/{id}', tags: ['Items'], summary: 'Replace an item',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ItemInput')),
        responses: [new OA\Response(response: 200, description: 'Item updated', content: new OA\JsonContent(ref: '#/components/schemas/Item'))])]
    public function replaceItem() {}
    #[OA\Patch(path: '/items/{id}', tags: ['Items'], summary: 'Update an item',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ItemInput')),
        responses: [new OA\Response(response: 200, description: 'Item updated', content: new OA\JsonContent(ref: '#/components/schemas/Item'))])]
    public function updateItem() {}
    #[OA\Delete(path: '/items/{id}', tags: ['Items'], summary: 'Delete an item',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Item deleted', content: new OA\JsonContent(ref: '#/components/schemas/DeleteResponse')),
            new OA\Response(response: 404, description: 'Item not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function deleteItem() {}
}
