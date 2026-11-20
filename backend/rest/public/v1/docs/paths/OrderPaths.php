<?php

use OpenApi\Attributes as OA;

class OrderPaths
{
    #[OA\Get(path: '/orders', tags: ['Orders'], summary: 'List orders',
        responses: [new OA\Response(response: 200, description: 'Orders', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Order')))])]
    public function listOrders() {}
    #[OA\Get(path: '/orders/by-buyer/{id}', tags: ['Orders'], summary: 'List orders by buyer',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Orders', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Order')))])]
    public function listOrdersByBuyer() {}
    #[OA\Get(path: '/orders/{id}', tags: ['Orders'], summary: 'Get an order',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Order', content: new OA\JsonContent(ref: '#/components/schemas/Order')),
            new OA\Response(response: 404, description: 'Order not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function getOrder() {}
    #[OA\Post(path: '/orders', tags: ['Orders'], summary: 'Create an order',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/OrderInput')),
        responses: [new OA\Response(response: 201, description: 'Order created', content: new OA\JsonContent(ref: '#/components/schemas/Order')),
            new OA\Response(response: 400, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function createOrder() {}
    #[OA\Put(path: '/orders/{id}', tags: ['Orders'], summary: 'Replace an order',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/OrderInput')),
        responses: [new OA\Response(response: 200, description: 'Order updated', content: new OA\JsonContent(ref: '#/components/schemas/Order'))])]
    public function replaceOrder() {}
    #[OA\Patch(path: '/orders/{id}', tags: ['Orders'], summary: 'Update an order',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/OrderInput')),
        responses: [new OA\Response(response: 200, description: 'Order updated', content: new OA\JsonContent(ref: '#/components/schemas/Order'))])]
    public function updateOrder() {}
    #[OA\Delete(path: '/orders/{id}', tags: ['Orders'], summary: 'Delete an order',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Order deleted', content: new OA\JsonContent(ref: '#/components/schemas/DeleteResponse'))])]
    public function deleteOrder() {}
}
