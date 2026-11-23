<?php

use OpenApi\Attributes as OA;

class ExchangeRatePaths
{
    #[OA\Get(
        path: '/exchange-rates/convert',
        tags: ['Exchange Rates'],
        summary: 'Convert a BAM price to another currency (live rates, external API)',
        parameters: [
            new OA\Parameter(name: 'amount', in: 'query', required: true,
                description: 'Price in BAM (greater than 0)', example: 25,
                schema: new OA\Schema(type: 'number')),
            new OA\Parameter(name: 'to', in: 'query', required: true,
                description: '3-letter target currency code', example: 'EUR',
                schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Converted amount',
                content: new OA\JsonContent(ref: '#/components/schemas/ConversionResult')),
            new OA\Response(response: 400, description: 'Missing or invalid amount / currency code',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Currency not supported',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 503, description: 'External service unavailable, too slow or not configured',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function getExchangeRatesConvert() {}
}