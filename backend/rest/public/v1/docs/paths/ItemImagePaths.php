<?php

use OpenApi\Attributes as OA;

class ItemImagePaths
{
    #[OA\Get(path: '/item-images', tags: ['Item Images'], summary: 'List item images',
        responses: [new OA\Response(response: 200, description: 'Images', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/ItemImage')))])]
    public function listItemImages() {}
    #[OA\Get(path: '/item-images/by-item/{id}', tags: ['Item Images'], summary: 'List images by item',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Images', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/ItemImage')))])]
    public function listItemImagesByItem() {}
    #[OA\Get(path: '/item-images/{id}', tags: ['Item Images'], summary: 'Get an item image',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Image', content: new OA\JsonContent(ref: '#/components/schemas/ItemImage')),
            new OA\Response(response: 404, description: 'Image not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function getItemImage() {}
    #[OA\Post(path: '/item-images', tags: ['Item Images'], summary: 'Create an item image',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ItemImageInput')),
        responses: [new OA\Response(response: 201, description: 'Image created', content: new OA\JsonContent(ref: '#/components/schemas/ItemImage')),
            new OA\Response(response: 400, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function createItemImage() {}
    #[OA\Put(path: '/item-images/{id}', tags: ['Item Images'], summary: 'Replace an item image',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ItemImageInput')),
        responses: [new OA\Response(response: 200, description: 'Image updated', content: new OA\JsonContent(ref: '#/components/schemas/ItemImage'))])]
    public function replaceItemImage() {}
    #[OA\Patch(path: '/item-images/{id}', tags: ['Item Images'], summary: 'Update an item image',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ItemImageInput')),
        responses: [new OA\Response(response: 200, description: 'Image updated', content: new OA\JsonContent(ref: '#/components/schemas/ItemImage'))])]
    public function updateItemImage() {}
    #[OA\Delete(path: '/item-images/{id}', tags: ['Item Images'], summary: 'Delete an item image',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Image deleted', content: new OA\JsonContent(ref: '#/components/schemas/DeleteResponse'))])]
    public function deleteItemImage() {}
}
