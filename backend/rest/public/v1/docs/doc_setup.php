<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Bosnian Thrift Shop API',
    description: 'REST API for users, categories, items, item images, orders, and reviews.'
)]
#[OA\Server(url: API_BASE_URL, description: 'This server')]
#[OA\Tag(name: 'Users', description: 'User accounts')]
#[OA\Tag(name: 'Categories', description: 'Item categories')]
#[OA\Tag(name: 'Items', description: 'Marketplace items')]
#[OA\Tag(name: 'Item Images', description: 'Item photo records')]
#[OA\Tag(name: 'Orders', description: 'Marketplace orders')]
#[OA\Tag(name: 'Reviews', description: 'Item reviews')]
#[OA\Tag(name: 'Exchange Rates', description: 'Currency conversion (external API)')]
#[OA\Schema(
    schema: 'Error',
    required: ['error', 'message'],
    properties: [
        new OA\Property(property: 'error', type: 'boolean', example: true),
        new OA\Property(property: 'message', type: 'string', example: 'Endpoint not found.')
    ]
)]
#[OA\Schema(
    schema: 'DeleteResponse',
    required: ['error', 'data'],
    properties: [
        new OA\Property(property: 'error', type: 'boolean', example: false),
        new OA\Property(property: 'data', type: 'object',
            properties: [new OA\Property(property: 'message', type: 'string', example: 'record deleted.')])
    ]
)]
#[OA\Schema(
    schema: 'UserInput',
    required: ['first_name', 'last_name', 'email', 'password'],
    properties: [
        new OA\Property(property: 'first_name', type: 'string', maxLength: 50),
        new OA\Property(property: 'last_name', type: 'string', maxLength: 50),
        new OA\Property(property: 'email', type: 'string', format: 'email'),
        new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8),
        new OA\Property(property: 'role', type: 'string', enum: ['user', 'admin', 'seller'])
    ]
)]
#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'user_id', type: 'integer', example: 1),
        new OA\Property(property: 'first_name', type: 'string', example: 'Amar'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Hodzic'),
        new OA\Property(property: 'email', type: 'string', format: 'email'),
        new OA\Property(property: 'role', type: 'string', enum: ['user', 'admin', 'seller']),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time')
    ]
)]
#[OA\Schema(
    schema: 'CategoryInput',
    required: ['name'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 50),
        new OA\Property(property: 'description', type: 'string', nullable: true)
    ]
)]
#[OA\Schema(
    schema: 'Category',
    properties: [
        new OA\Property(property: 'category_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Clothing & Apparel'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'item_count', type: 'integer', example: 2)
    ]
)]
#[OA\Schema(
    schema: 'ItemInput',
    required: ['seller_id', 'category_id', 'name', 'description', 'price'],
    properties: [
        new OA\Property(property: 'seller_id', type: 'integer'),
        new OA\Property(property: 'category_id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string', maxLength: 100),
        new OA\Property(property: 'description', type: 'string'),
        new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0.01),
        new OA\Property(property: 'rating', type: 'number', format: 'float', minimum: 0, maximum: 5),
        new OA\Property(property: 'status', type: 'string', enum: ['available', 'sold'])
    ]
)]
#[OA\Schema(
    schema: 'Item',
    properties: [
        new OA\Property(property: 'item_id', type: 'integer'),
        new OA\Property(property: 'seller_id', type: 'integer'),
        new OA\Property(property: 'category_id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'description', type: 'string'),
        new OA\Property(property: 'price', type: 'number', format: 'float'),
        new OA\Property(property: 'rating', type: 'number', format: 'float'),
        new OA\Property(property: 'status', type: 'string', enum: ['available', 'sold']),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time')
    ]
)]
#[OA\Schema(
    schema: 'ItemImageInput',
    required: ['item_id', 'image_url'],
    properties: [
        new OA\Property(property: 'item_id', type: 'integer'),
        new OA\Property(property: 'image_url', type: 'string', format: 'uri', maxLength: 255),
        new OA\Property(property: 'is_primary', type: 'boolean')
    ]
)]
#[OA\Schema(
    schema: 'ItemImage',
    properties: [
        new OA\Property(property: 'image_id', type: 'integer'),
        new OA\Property(property: 'item_id', type: 'integer'),
        new OA\Property(property: 'image_url', type: 'string', format: 'uri'),
        new OA\Property(property: 'is_primary', type: 'boolean')
    ]
)]
#[OA\Schema(
    schema: 'OrderInput',
    required: ['buyer_id', 'item_id', 'total_price'],
    properties: [
        new OA\Property(property: 'buyer_id', type: 'integer'),
        new OA\Property(property: 'item_id', type: 'integer'),
        new OA\Property(property: 'total_price', type: 'number', format: 'float', minimum: 0.01)
    ]
)]
#[OA\Schema(
    schema: 'Order',
    properties: [
        new OA\Property(property: 'order_id', type: 'integer'),
        new OA\Property(property: 'buyer_id', type: 'integer'),
        new OA\Property(property: 'item_id', type: 'integer'),
        new OA\Property(property: 'total_price', type: 'number', format: 'float'),
        new OA\Property(property: 'order_date', type: 'string', format: 'date-time')
    ]
)]
#[OA\Schema(
    schema: 'ReviewInput',
    required: ['item_id', 'user_id', 'rating'],
    properties: [
        new OA\Property(property: 'item_id', type: 'integer'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5),
        new OA\Property(property: 'comment', type: 'string', nullable: true, maxLength: 1000)
    ]
)]
#[OA\Schema(
    schema: 'Review',
    properties: [
        new OA\Property(property: 'review_id', type: 'integer'),
        new OA\Property(property: 'item_id', type: 'integer'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5),
        new OA\Property(property: 'comment', type: 'string', nullable: true),
        new OA\Property(property: 'date', type: 'string', format: 'date-time')
    ]
)]
#[OA\Schema(
    schema: 'ConversionResult',
    properties: [
        new OA\Property(property: 'from', type: 'string', example: 'BAM'),
        new OA\Property(property: 'to', type: 'string', example: 'EUR'),
        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 25),
        new OA\Property(property: 'rate', type: 'number', format: 'float', example: 0.51),
        new OA\Property(property: 'converted', type: 'number', format: 'float', example: 12.75),
        new OA\Property(property: 'source', type: 'string', example: 'exchangerate-api.com')
    ]
)]
class OpenApiSpec {}
