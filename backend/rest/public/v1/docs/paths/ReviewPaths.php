<?php

use OpenApi\Attributes as OA;

class ReviewPaths
{
    #[OA\Get(path: '/reviews', tags: ['Reviews'], summary: 'List reviews',
        responses: [new OA\Response(response: 200, description: 'Reviews', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Review')))])]
    public function listReviews() {}
    #[OA\Get(path: '/reviews/by-item/{id}', tags: ['Reviews'], summary: 'List reviews by item',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Reviews', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Review')))])]
    public function listReviewsByItem() {}
    #[OA\Get(path: '/reviews/{id}', tags: ['Reviews'], summary: 'Get a review',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Review', content: new OA\JsonContent(ref: '#/components/schemas/Review')),
            new OA\Response(response: 404, description: 'Review not found', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function getReview() {}
    #[OA\Post(path: '/reviews', tags: ['Reviews'], summary: 'Create a review',
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ReviewInput')),
        responses: [new OA\Response(response: 201, description: 'Review created', content: new OA\JsonContent(ref: '#/components/schemas/Review')),
            new OA\Response(response: 400, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/Error'))])]
    public function createReview() {}
    #[OA\Put(path: '/reviews/{id}', tags: ['Reviews'], summary: 'Replace a review',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ReviewInput')),
        responses: [new OA\Response(response: 200, description: 'Review updated', content: new OA\JsonContent(ref: '#/components/schemas/Review'))])]
    public function replaceReview() {}
    #[OA\Patch(path: '/reviews/{id}', tags: ['Reviews'], summary: 'Update a review',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ReviewInput')),
        responses: [new OA\Response(response: 200, description: 'Review updated', content: new OA\JsonContent(ref: '#/components/schemas/Review'))])]
    public function updateReview() {}
    #[OA\Delete(path: '/reviews/{id}', tags: ['Reviews'], summary: 'Delete a review',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', minimum: 1))],
        responses: [new OA\Response(response: 200, description: 'Review deleted', content: new OA\JsonContent(ref: '#/components/schemas/DeleteResponse'))])]
    public function deleteReview() {}
}
